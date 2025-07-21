<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleMake;
use App\Models\VehicleModel;
use App\Models\VehicleModelYear;

class VehicleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear 2 marcas de vehículos
        $makes = [
            [
                'name' => 'Toyota',
                'vpic_id' => 'toyota',
                'models' => [
                    ['name' => 'Camry', 'vpic_id' => 'camry'],
                    ['name' => 'Corolla', 'vpic_id' => 'corolla'],
                    ['name' => 'RAV4', 'vpic_id' => 'rav4'],
                    ['name' => 'Highlander', 'vpic_id' => 'highlander'],
                    ['name' => 'Tacoma', 'vpic_id' => 'tacoma'],
                ]
            ],
            [
                'name' => 'Honda',
                'vpic_id' => 'honda',
                'models' => [
                    ['name' => 'Civic', 'vpic_id' => 'civic'],
                    ['name' => 'Accord', 'vpic_id' => 'accord'],
                    ['name' => 'CR-V', 'vpic_id' => 'cr-v'],
                    ['name' => 'Pilot', 'vpic_id' => 'pilot'],
                    ['name' => 'Ridgeline', 'vpic_id' => 'ridgeline'],
                ]
            ]
        ];

        foreach ($makes as $makeData) {
            // Crear la marca
            $make = VehicleMake::create([
                'name' => $makeData['name'],
                'vpic_id' => $makeData['vpic_id'],
            ]);

            // Crear 5 modelos para cada marca
            foreach ($makeData['models'] as $modelData) {
                $model = VehicleModel::create([
                    'name' => $modelData['name'],
                    'vpic_id' => $modelData['vpic_id'],
                    'make_id' => $make->id,
                ]);

                // Crear 2 años para cada modelo (2023 y 2024)
                for ($year = 2023; $year <= 2024; $year++) {
                    VehicleModelYear::create([
                        'year' => (string) $year,
                        'vpic_id' => $modelData['vpic_id'] . '_' . $year,
                        'model_id' => $model->id,
                    ]);
                }
            }
        }

        $this->command->info('Se han creado 2 marcas, 10 modelos y 20 años de vehículos.');
    }
} 