<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;
use App\Models\ClientNotification;

class ClientNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $client = Client::first();
        if (!$client) {
            return;
        }
        //action_type can be redirect, info, external url
        $notifications = [
            [
                'client_id' => $client->id,
                'title' => 'Mantenimiento Programado',
                'message' => 'Tu vehículo Hyundai Creta tiene un mantenimiento programado para el próximo mes.',
                'payload' => [
                    "action_type" => "redirect",
                    "route" => "vehicle.services",
                    "params" => [
                        "id" => 1
                    ],
                    "url" => null
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'Promoción Especial',
                'message' => '¡Aprovecha nuestro descuento del 20% en cambio de aceite!',
                'payload' => [
                    "action_type" => "redirect",
                    "route" => "promotions",
                    "params" => [],
                    "url" => null
                ],
                'read' => 'Y',
                'status' => 'A',
                'read_at' => now()->subDays(2)->format('Y-m-d'),
            ],
            [
                'client_id' => $client->id,
                'title' => 'Promoción Especial',
                'message' => '¡Aprovecha nuestro descuento del 20% en cambio de aceite!',
                'payload' => [
                    "action_type" => "info",
                    "route" => null,
                    "params" => [],
                    "url" => null
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'Promoción Especial',
                'message' => '¡Aprovecha nuestro descuento del 20% en cambio de aceite!',
                'payload' => [
                    "action_type" => "url",
                    "route" => null,
                    "params" => [],
                    "url" => "https://www.google.com"
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'Recordatorio de Servicio',
                'message' => 'Tu vehículo necesita revisión de frenos. Agenda tu cita hoy.',
                'payload' => [
                    "action_type" => "redirect",
                    "route" => "vehicle.services",
                    "params" => [
                        "id" => 2
                    ],
                    "url" => null
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'Oferta de Fin de Mes',
                'message' => 'Últimos días para aprovechar el 30% de descuento en diagnóstico completo.',
                'payload' => [
                    "action_type" => "redirect",
                    "route" => "promotions",
                    "params" => [],
                    "url" => null
                ],
                'read' => 'Y',
                'status' => 'A',
                'read_at' => now()->subDays(1)->format('Y-m-d'),
            ],
            [
                'client_id' => $client->id,
                'title' => 'Bienvenido a Hyundai Rexville',
                'message' => 'Gracias por confiar en nosotros. Tu vehículo está en buenas manos.',
                'payload' => [
                    "action_type" => "info",
                    "route" => null,
                    "params" => [],
                    "url" => null
                ],
                'read' => 'Y',
                'status' => 'A',
                'read_at' => now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'client_id' => $client->id,
                'title' => 'Manual del Propietario',
                'message' => 'Descarga el manual completo de tu vehículo para conocer todas sus funciones.',
                'payload' => [
                    "action_type" => "url",
                    "route" => null,
                    "params" => [],
                    "url" => "https://www.hyundai.com/manual"
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'Cambio de Aceite Recomendado',
                'message' => 'Tu vehículo ha alcanzado los 5,000 km. Es momento de cambiar el aceite.',
                'payload' => [
                    "action_type" => "redirect",
                    "route" => "vehicle.services",
                    "params" => [
                        "id" => 3
                    ],
                    "url" => null
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'Descuento en Neumáticos',
                'message' => 'Obtén 15% de descuento en la compra de 4 neumáticos Michelin.',
                'payload' => [
                    "action_type" => "redirect",
                    "route" => "promotions",
                    "params" => [],
                    "url" => null
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'Información Importante',
                'message' => 'Recuerda que tu garantía cubre defectos de fabricación por 5 años.',
                'payload' => [
                    "action_type" => "info",
                    "route" => null,
                    "params" => [],
                    "url" => null
                ],
                'read' => 'N',
                'status' => 'A',
                'read_at' => null,
            ],
            [
                'client_id' => $client->id,
                'title' => 'App Hyundai Connect',
                'message' => 'Descarga nuestra app para monitorear tu vehículo en tiempo real.',
                'payload' => [
                    "action_type" => "url",
                    "route" => null,
                    "params" => [],
                    "url" => "https://play.google.com/store/apps/hyundai"
                ],
                'read' => 'Y',
                'status' => 'A',
                'read_at' => now()->subDays(3)->format('Y-m-d'),
            ]
        ];

        foreach ($notifications as $notificationData) {
            ClientNotification::create($notificationData);
        }

        $this->command->info('Se han creado 12 notificaciones para el cliente: ' . $client->name);
    }
}
