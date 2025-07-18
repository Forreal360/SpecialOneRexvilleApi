<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin principal
        DB::table('admins')->insert([
            'name' => 'User',
            'last_name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('234975'),
            'phone_code' => '57',
            'phone' => '3001234567',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('admins')->insert([
            'name' => 'User',
            'last_name' => 'Admin',
            'email' => 'joanmilla21@gmail.com',
            'password' => Hash::make('234975'),
            'phone_code' => '57',
            'phone' => '3001234567',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Generar 50 usuarios aleatorios
        $faker = Faker::create('es_ES');
        $admins = [];

        for ($i = 0; $i < 50; $i++) {
            $admins[] = [
                'name' => $faker->firstName(),
                'last_name' => $faker->lastName(),
                'email' => $faker->unique()->safeEmail(),
                'password' => Hash::make('password123'),
                'phone_code' => $faker->randomElement(['57', '1', '34', '52', '54']),
                'phone' => $faker->numerify('##########'),
                'license_number' => $faker->optional(0.7)->numerify('LIC-####-####'),
                'profile_photo' => null,
                'status' => $faker->randomElement(['A', 'A', 'A', 'I']), // Mayor probabilidad de activos
                'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                'updated_at' => now(),
            ];
        }

        DB::table('admins')->insert($admins);
    }
}
