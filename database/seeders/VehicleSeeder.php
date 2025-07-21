<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = \App\Models\Client::first();

        if (!$client) {
            return;
        }

        $vehicles = [
            [
                'client_id' => $client->id,
                'year' => 2025,
                'make_id' => '1',
                'model_id' => '2',
                'vin' => '1HGBH41JXMN109186',
                'buy_date' => '2020-03-15',
                'insurance' => 'Seguros Bolívar',
                'image_path' => 'promotions/fHzIN3phMEYFVfRdYqpzXdozn63mlXmOdS3F6adm.png'
            ],
            [
                'client_id' => $client->id,
                'year' => 2025,
                'make_id' => '1',
                'model_id' => '2',
                'vin' => '2T1BURHE0JC123456',
                'buy_date' => '2019-07-22',
                'insurance' => 'Mapfre Seguros',
                'image_path' => 'promotions/fHzIN3phMEYFVfRdYqpzXdozn63mlXmOdS3F6adm.png'
            ],
            [
                'client_id' => $client->id,
                'year' => 2025,
                'make_id' => '1',
                'model_id' => '2',
                'vin' => '3VWDX7AJ5DM123456',
                'buy_date' => '2021-01-10',
                'insurance' => 'Sura Seguros',
                'image_path' => 'promotions/fHzIN3phMEYFVfRdYqpzXdozn63mlXmOdS3F6adm.png'
            ],
            [
                'client_id' => $client->id,
                'year' => 2025,
                'make_id' => '1',
                'model_id' => '2',
                'vin' => '4T1B11HK5JU123456',
                'buy_date' => '2018-11-05',
                'insurance' => 'Colpatria Seguros',
                'image_path' => 'promotions/fHzIN3phMEYFVfRdYqpzXdozn63mlXmOdS3F6adm.png'
            ],
            [
                'client_id' => $client->id,
                'year' => 2025,
                'make_id' => '1',
                'model_id' => '2',
                'vin' => '5YJSA1E47HF123456',
                'buy_date' => '2022-05-18',
                'insurance' => 'Allianz Seguros',
                'image_path' => 'promotions/fHzIN3phMEYFVfRdYqpzXdozn63mlXmOdS3F6adm.png'
            ]
        ];

        foreach ($vehicles as $vehicleData) {
            \App\Models\ClientVehicle::create($vehicleData);
        }

        $this->command->info('Se han creado 5 vehículos para el cliente: ' . $client->name);
    }
}
