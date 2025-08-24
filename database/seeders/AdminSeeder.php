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
            'super_admin' => 'Y',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('234975'),
            'phone_code' => '57',
            'phone' => '3001234567',
            'profile_photo' => null,
            'status' => 'A',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

    }
}
