<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $promotions = [
            [
                'title' => 'Promoción de Navidad',
                'start_date' => now(),
                'end_date' => now()->addMonth(),
                'image_url' => 'https://example.com/images/promo-navidad.jpg',
                'redirect_url' => 'https://example.com/navidad',
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Oferta de Año Nuevo',
                'start_date' => now()->subWeek(),
                'end_date' => now()->addWeeks(2),
                'image_url' => 'https://example.com/images/promo-año-nuevo.jpg',
                'redirect_url' => 'https://example.com/año-nuevo',
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'Descuento de Verano',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(30),
                'image_url' => 'https://example.com/images/promo-verano.jpg',
                'redirect_url' => 'https://example.com/verano',
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($promotions as $promotion) {
            \App\Models\Promotion::create($promotion);
        }
    }
}
