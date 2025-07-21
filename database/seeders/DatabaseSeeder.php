<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            VehicleMakesModelsUpdateSeeder::class,
            UserSeeder::class,
            PromotionSeeder::class,
            VehicleServiceSeeder::class,
            #VehicleDataSeeder::class,
            VehicleSeeder::class,
            ServiceSeeder::class,
            ClientNotificationSeeder::class,
            SocialAccountSeeder::class,
            AdminSeeder::class,
        ]);
    }
}
