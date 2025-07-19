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
                'year' => 2020,
                'model' => 'Hyundai Creta',
                'vin' => '1HGBH41JXMN109186',
                'buy_date' => '2020-03-15',
                'insurance' => 'Seguros Bolívar'
            ],
            [
                'client_id' => $client->id,
                'year' => 2019,
                'model' => 'Hyundai Tucson',
                'vin' => '2T1BURHE0JC123456',
                'buy_date' => '2019-07-22',
                'insurance' => 'Mapfre Seguros'
            ],
            [
                'client_id' => $client->id,
                'year' => 2021,
                'model' => 'Hyundai Santa Fe',
                'vin' => '3VWDX7AJ5DM123456',
                'buy_date' => '2021-01-10',
                'insurance' => 'Sura Seguros'
            ],
            [
                'client_id' => $client->id,
                'year' => 2018,
                'model' => 'Hyundai Elantra',
                'vin' => '4T1B11HK5JU123456',
                'buy_date' => '2018-11-05',
                'insurance' => 'Colpatria Seguros'
            ],
            [
                'client_id' => $client->id,
                'year' => 2022,
                'model' => 'Hyundai Accent',
                'vin' => '5YJSA1E47HF123456',
                'buy_date' => '2022-05-18',
                'insurance' => 'Allianz Seguros'
            ]
        ];

        foreach ($vehicles as $vehicleData) {
            \App\Models\ClientVehicle::create($vehicleData);
        }

        $this->command->info('Se han creado 5 vehículos para el cliente: ' . $client->name);
    }
}
