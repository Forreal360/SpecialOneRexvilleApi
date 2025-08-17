<?php

namespace Database\Seeders;

use App\Models\AdminNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $idAdmin = 1;

        for ($i = 0; $i < 50; $i++) {
            AdminNotification::create([
                'admin_id' => $idAdmin,
                'title' => 'Notification ' . $i,
                'message' => 'Message ' . $i,
                'date' => now(),
                'action' => 'action ' . $i,
                'payload' => [],
            ]);

        }
    }
}
