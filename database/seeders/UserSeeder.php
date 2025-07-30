<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            'name' => 'Joan',
            'last_name' => 'Milla',
            'email' => 'joanmilla21@gmail.com',
            'password' => Hash::make('234975'),
            'phone_code' => '57',
            'phone' => '3001234567',
            'license_number' => 'LIC123456789',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('clients')->insert([
            'name' => 'Esbozo',
            'last_name' => 'ID',
            'email' => 'esbozo.id@gmail.com',
            'password' => Hash::make('12345678'),
            'phone_code' => '57',
            'phone' => '3001234568',
            'license_number' => 'LIC123456790',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('clients')->insert([
            'name' => 'Masol',
            'last_name' => 'User',
            'email' => 'masol_080889@hotmail.com',
            'password' => Hash::make('12345678'),
            'phone_code' => '57',
            'phone' => '3001234569',
            'license_number' => 'LIC123456791',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('clients')->insert([
            'name' => 'Darisay',
            'last_name' => 'Blanco',
            'email' => 'darisayblanco19@gmail.com',
            'password' => Hash::make('12345678'),
            'phone_code' => '57',
            'phone' => '3001234570',
            'license_number' => 'LIC123456792',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('clients')->insert([
            'name' => 'Telly',
            'last_name' => 'Miranda',
            'email' => 'tellymiranda@gmail.com',
            'password' => Hash::make('12345678'),
            'phone_code' => '57',
            'phone' => '3001234571',
            'license_number' => 'LIC123456793',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
