<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class VehicleMakesModelsSeeder extends Seeder
{
    /**
     * Las marcas específicas que queremos cargar
     */
    private array $targetMakes = [
        'HYUNDAI',
        'JEEP',
        'NISSAN',
        'INFINITI',
        'KIA',
        'TOYOTA',
        'FORD',
        'MITSUBISHI'
    ];

    /**
     * Años a consultar (últimos 10 años)
     */
    private array $years;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->years = range(date('Y'), date('Y') - 10);

        $this->command->info('�� Iniciando carga de marcas, modelos y años de vehículos...');

        try {
            // Limpiar tablas existentes
            $this->clearExistingData();

            // Cargar marcas
            $this->loadMakes();

            // Cargar modelos y años para cada marca
            $this->loadModelsAndYears();

            $this->command->info('✅ Carga completada exitosamente!');

        } catch (\Exception $e) {
            $this->command->error('❌ Error durante la carga: ' . $e->getMessage());
            Log::error('Error en VehicleMakesModelsSeeder: ' . $e->getMessage());
        }
    }

    /**
     * Limpiar datos existentes
     */
    private function clearExistingData(): void
    {
        $this->command->info('🧹 Limpiando datos existentes...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('vehicle_model_years')->truncate();
        DB::table('vehicle_models')->truncate();
        DB::table('vehicle_makes')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Datos limpiados');
    }

    /**
     * Cargar marcas desde la API de NHTSA
     */
    private function loadMakes(): void
    {
        $this->command->info('�� Cargando marcas...');

        $url = "https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json";
        $response = $this->makeApiRequest($url);

        if (!$response || !isset($response['Results'])) {
            throw new \Exception('No se pudieron obtener las marcas desde la API');
        }

        $makesToInsert = [];
        $foundMakes = 0;

        foreach ($response['Results'] as $make) {
            if (in_array(strtoupper($make['Make_Name']), $this->targetMakes)) {
                $makesToInsert[] = [
                    'name' => $make['Make_Name'],
                    'vpic_id' => $make['Make_ID'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $foundMakes++;
            }
        }

        if (empty($makesToInsert)) {
            throw new \Exception('No se encontraron las marcas especificadas en la API');
        }

        DB::table('vehicle_makes')->insert($makesToInsert);

        $this->command->info("✅ Se cargaron {$foundMakes} marcas");
    }

    /**
     * Cargar modelos y años para cada marca
     */
    private function loadModelsAndYears(): void
    {
        $this->command->info('�� Cargando modelos y años...');

        $makes = DB::table('vehicle_makes')->get();
        $totalModels = 0;
        $totalYears = 0;

        foreach ($makes as $make) {
            $this->command->info("📋 Procesando marca: {$make->name}");

            // Obtener modelos para esta marca
            $models = $this->getModelsForMake($make->name);

            foreach ($models as $modelData) {
                // Insertar modelo
                $modelId = DB::table('vehicle_models')->insertGetId([
                    'name' => $modelData['Model_Name'],
                    'vpic_id' => $modelData['Model_ID'],
                    'make_id' => $make->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalModels++;

                // Obtener años disponibles para este modelo
                $years = $this->getYearsForModel($make->name, $modelData['Model_Name']);

                if (!empty($years)) {
                    $yearsToInsert = [];
                    foreach ($years as $year) {
                        $yearsToInsert[] = [
                            'year' => $year,
                            'vpic_id' => null, // La API no proporciona ID específico para años
                            'model_id' => $modelId,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $totalYears++;
                    }

                    DB::table('vehicle_model_years')->insert($yearsToInsert);
                }

                // Pausa para evitar rate limiting
                usleep(500000); // 0.5 segundos
            }
        }

        $this->command->info("✅ Se cargaron {$totalModels} modelos y {$totalYears} años");
    }

    /**
     * Obtener modelos para una marca específica
     */
    private function getModelsForMake(string $makeName): array
    {
        $url = "https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformake/{$makeName}?format=json";
        $response = $this->makeApiRequest($url);

        if (!$response || !isset($response['Results'])) {
            $this->command->warn("⚠️ No se pudieron obtener modelos para {$makeName}");
            return [];
        }

        return $response['Results'];
    }

    /**
     * Obtener años disponibles para un modelo específico
     */
    private function getYearsForModel(string $makeName, string $modelName): array
    {
        $availableYears = [];

        foreach ($this->years as $year) {
            $url = "https://vpic.nhtsa.dot.gov/api/vehicles/getmodelsformakeyear/make/{$makeName}/modelyear/{$year}?format=json";
            $response = $this->makeApiRequest($url);

            if ($response && isset($response['Results'])) {
                foreach ($response['Results'] as $result) {
                    if (strcasecmp($result['Model_Name'], $modelName) === 0) {
                        $availableYears[] = $year;
                        break;
                    }
                }
            }

            // Pausa más corta para consultas de años
            usleep(200000); // 0.2 segundos
        }

        return $availableYears;
    }

    /**
     * Realizar petición a la API con manejo de errores y cache
     */
    private function makeApiRequest(string $url): ?array
    {
        $cacheKey = 'nhtsa_api_' . md5($url);

        // Intentar obtener del cache primero
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        try {
            $context = stream_context_create([
                'http' => [
                    'timeout' => 30,
                    'user_agent' => 'SpecialOneRexvilleApi/1.0'
                ]
            ]);

            $response = file_get_contents($url, false, $context);

            if ($response === false) {
                throw new \Exception("Error al conectar con la API: {$url}");
            }

            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception("Error al decodificar JSON de la respuesta");
            }

            // Cache por 24 horas
            Cache::put($cacheKey, $data, 86400);

            return $data;

        } catch (\Exception $e) {
            $this->command->warn("⚠️ Error en API request: " . $e->getMessage());
            Log::warning("API request failed: {$url} - " . $e->getMessage());
            return null;
        }
    }
}
