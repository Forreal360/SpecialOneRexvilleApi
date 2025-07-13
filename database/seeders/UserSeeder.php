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
            'name' => 'Joan Milla',
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
    }
}
