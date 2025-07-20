<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleModelYear;

class VehicleMakesModelsUpdateSeeder extends Seeder
{
    /**
     * Las marcas específicas que queremos cargar
     */
    private array $targetMakes = [
        'HYUNDAI',
        'JEEP',
        /*'NISSAN',
        'INFINITI',
        'KIA',
        'TOYOTA',
        'FORD',
        'MITSUBISHI' */
    ];

    /**
     * Años a consultar (últimos 10 años)
     */
    private array $years;

    /**
     * Contadores para el reporte
     */
    private array $counters = [
        'makes_created' => 0,
        'makes_updated' => 0,
        'models_created' => 0,
        'models_updated' => 0,
        'years_created' => 0,
        'years_updated' => 0,
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->years = range(date('Y'), date('Y') - 2);

        $this->command->info('🔄 Iniciando actualización de marcas, modelos y años de vehículos...');

        try {
            // Actualizar marcas
            $this->updateMakes();

            // Actualizar modelos y años para cada marca
            $this->updateModelsAndYears();

            // Mostrar reporte final
            $this->showReport();

            $this->command->info('✅ Actualización completada exitosamente!');

        } catch (\Exception $e) {
            $this->command->error('❌ Error durante la actualización: ' . $e->getMessage());
            Log::error('Error en VehicleMakesModelsUpdateSeeder: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar marcas desde la API de NHTSA
     */
    private function updateMakes(): void
    {
        $this->command->info('🔄 Actualizando marcas...');

        $url = "https://vpic.nhtsa.dot.gov/api/vehicles/getallmakes?format=json";
        $response = $this->makeApiRequest($url);

        if (!$response || !isset($response['Results'])) {
            throw new \Exception('No se pudieron obtener las marcas desde la API');
        }

        foreach ($response['Results'] as $make) {
            if (in_array(strtoupper($make['Make_Name']), $this->targetMakes)) {
                $existingMake = VehicleMake::where('vpic_id', $make['Make_ID'])
                    ->orWhere('name', $make['Make_Name'])
                    ->first();

                if ($existingMake) {
                    // Actualizar marca existente
                    $existingMake->update([
                        'name' => $make['Make_Name'],
                        'vpic_id' => $make['Make_ID'],
                    ]);
                    $this->counters['makes_updated']++;
                } else {
                    // Crear nueva marca
                    VehicleMake::create([
                        'name' => $make['Make_Name'],
                        'vpic_id' => $make['Make_ID'],
                    ]);
                    $this->counters['makes_created']++;
                }
            }
        }

        $this->command->info("✅ Marcas procesadas: {$this->counters['makes_created']} creadas, {$this->counters['makes_updated']} actualizadas");
    }

    /**
     * Actualizar modelos y años para cada marca
     */
    private function updateModelsAndYears(): void
    {
        $this->command->info('🔄 Actualizando modelos y años...');

        $makes = VehicleMake::all();

        foreach ($makes as $make) {
            $this->command->info("📋 Procesando marca: {$make->name}");

            // Obtener modelos para esta marca
            $models = $this->getModelsForMake($make->name);

            foreach ($models as $modelData) {
                // Buscar o crear modelo
                $existingModel = VehicleModel::where('vpic_id', $modelData['Model_ID'])
                    ->orWhere(function($query) use ($modelData, $make) {
                        $query->where('name', $modelData['Model_Name'])
                              ->where('make_id', $make->id);
                    })
                    ->first();

                $model = null;

                if ($existingModel) {
                    // Actualizar modelo existente
                    $existingModel->update([
                        'name' => $modelData['Model_Name'],
                        'vpic_id' => $modelData['Model_ID'],
                        'make_id' => $make->id,
                    ]);
                    $model = $existingModel;
                    $this->counters['models_updated']++;
                } else {
                    // Crear nuevo modelo
                    $model = VehicleModel::create([
                        'name' => $modelData['Model_Name'],
                        'vpic_id' => $modelData['Model_ID'],
                        'make_id' => $make->id,
                    ]);
                    $this->counters['models_created']++;
                }

                // Actualizar años para este modelo
                $this->updateYearsForModel($model, $make->name, $modelData['Model_Name']);

                // Pausa para evitar rate limiting
                usleep(500000); // 0.5 segundos
            }
        }

        $this->command->info("✅ Modelos procesados: {$this->counters['models_created']} creados, {$this->counters['models_updated']} actualizados");
    }

    /**
     * Actualizar años disponibles para un modelo específico
     */
    private function updateYearsForModel(VehicleModel $model, string $makeName, string $modelName): void
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

        // Actualizar años para este modelo
        foreach ($availableYears as $year) {
            $existingYear = VehicleModelYear::where('model_id', $model->id)
                ->where('year', $year)
                ->first();

            if ($existingYear) {
                // Actualizar año existente
                $existingYear->touch(); // Actualiza updated_at
                $this->counters['years_updated']++;
            } else {
                // Crear nuevo año
                VehicleModelYear::create([
                    'year' => $year,
                    'vpic_id' => null,
                    'model_id' => $model->id,
                ]);
                $this->counters['years_created']++;
            }
        }
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

    /**
     * Mostrar reporte final de la actualización
     */
    private function showReport(): void
    {
        $this->command->newLine();
        $this->command->info('📊 REPORTE DE ACTUALIZACIÓN:');
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');
        $this->command->info("🏭 Marcas: {$this->counters['makes_created']} creadas, {$this->counters['makes_updated']} actualizadas");
        $this->command->info("🚗 Modelos: {$this->counters['models_created']} creados, {$this->counters['models_updated']} actualizados");
        $this->command->info("📅 Años: {$this->counters['years_created']} creados, {$this->counters['years_updated']} actualizados");
        $this->command->info('━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━');

        // Mostrar estadísticas de la base de datos usando modelos
        $totalMakes = VehicleMake::count();
        $totalModels = VehicleModel::count();
        $totalYears = VehicleModelYear::count();

        $this->command->info("�� TOTALES EN BASE DE DATOS:");
        $this->command->info("�� Marcas totales: {$totalMakes}");
        $this->command->info("🚗 Modelos totales: {$totalModels}");
        $this->command->info("📅 Años totales: {$totalYears}");

        // Mostrar algunas estadísticas adicionales
        $this->command->info("📈 ESTADÍSTICAS ADICIONALES:");
        $this->command->info("🏭 Marca con más modelos: " . $this->getMakeWithMostModels());
        $this->command->info("🚗 Modelo con más años: " . $this->getModelWithMostYears());
    }

    /**
     * Obtener la marca con más modelos
     */
    private function getMakeWithMostModels(): string
    {
        $make = VehicleMake::withCount('models')
            ->orderBy('models_count', 'desc')
            ->first();

        return $make ? "{$make->name} ({$make->models_count} modelos)" : "N/A";
    }

    /**
     * Obtener el modelo con más años
     */
    private function getModelWithMostYears(): string
    {
        $model = VehicleModel::withCount('years')
            ->orderBy('years_count', 'desc')
            ->first();

        return $model ? "{$model->name} ({$model->years_count} años)" : "N/A";
    }
}
