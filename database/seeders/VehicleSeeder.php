<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\VehicleModel as Model;
use Faker\Factory as Faker;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $clients = Client::all();
        $models = Model::where('make_id', 1)->get();
        $insuranceCompanies = [
            'Seguros Bolívar',
            'Mapfre Seguros',
            'Sura Seguros',
            'Colpatria Seguros',
            'Allianz Seguros',
            'Liberty Seguros',
            'AXA Seguros'
        ];

        foreach ($clients as $client) {
            // Generar entre 1 y 3 vehículos por cliente
            $numberOfVehicles = rand(1, 3);
            
            for ($i = 0; $i < $numberOfVehicles; $i++) {
                $randomModel = $models->random();
                
                \App\Models\ClientVehicle::create([
                    'client_id' => $client->id,
                    'year' => $faker->numberBetween(2015, 2025),
                    'make_id' => '1',
                    'model_id' => $randomModel->id,
                    'vin' => strtoupper($faker->bothify('?##???#?#?#######')),
                    'buy_date' => $faker->dateTimeBetween('-5 years', 'now')->format('Y-m-d'),
                    'insurance' => $faker->randomElement($insuranceCompanies),
                    'image_path' => 'promotions/fHzIN3phMEYFVfRdYqpzXdozn63mlXmOdS3F6adm.png'
                ]);
            }

            $this->command->info("Se han creado {$numberOfVehicles} vehículos para el cliente: {$client->name}");
        }
    }
}
