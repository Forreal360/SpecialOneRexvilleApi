<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\SocialAccount;

class SocialAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunos clientes existentes
        $clients = Client::take(1)->get();

        foreach ($clients as $client) {
            // Crear cuentas sociales de ejemplo
            SocialAccount::create([
                'client_id' => $client->id,
                'provider' => 'google',
                'provider_user_id' => 'google_' . $client->id . '_' . time(),
                'email' => $client->email,
                'name' => $client->name,
                'avatar' => 'https://via.placeholder.com/150',
                'provider_data' => [
                    'given_name' => $client->name,
                    'family_name' => '',
                    'picture' => 'https://via.placeholder.com/150'
                ]
            ]);

            SocialAccount::create([
                'client_id' => $client->id,
                'provider' => 'facebook',
                'provider_user_id' => 'facebook_' . $client->id . '_' . time(),
                'email' => $client->email,
                'name' => $client->name,
            ]);

            SocialAccount::create([
                'client_id' => $client->id,
                'provider' => 'apple',
                'provider_user_id' => 'apple_' . $client->id . '_' . time(),
                'email' => $client->email,
                'name' => $client->name,
            ]);
        }
    }
}
