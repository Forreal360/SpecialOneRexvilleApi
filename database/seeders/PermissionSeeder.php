<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $permissions = [

            // Administrators
            [
                "name" => "administrators.get",
                "module" => "administrators",
                "action" => "get",
                "alias" => "get_administrators"
            ],
            [
                "name" => "administrators.create",
                "module" => "administrators",
                "action" => "create",
                "alias" => "create_administrators"
            ],
            [
                "name" => "administrators.update",
                "module" => "administrators",
                "action" => "update",
                "alias" => "update_administrators"
            ],

            [
                "name" => "administrators.update-status",
                "module" => "administrators",
                "action" => "update",
                "alias" => "update_administrators_status"
            ],

            // Promotions
            [
                "name" => "promotions.get",
                "module" => "promotions",
                "action" => "get",
                "alias" => "get_promotions"
            ],
            [
                "name" => "promotions.create",
                "module" => "promotions",
                "action" => "create",
                "alias" => "create_promotions"
            ],
            [
                "name" => "promotions.update",
                "module" => "promotions",
                "action" => "update",
                "alias" => "update_promotions"
            ],
            [
                "name" => "promotions.update-status",
                "module" => "promotions",
                "action" => "update",
                "alias" => "update_promotions_status"
            ],

            // Vehicle Services
            [
                "name" => "vehicle-services.get",
                "module" => "vehicle-services",
                "action" => "get",
                "alias" => "get_vehicle_services"
            ],
            [
                "name" => "vehicle-services.create",
                "module" => "vehicle-services",
                "action" => "create",
                "alias" => "create_vehicle_services"
            ],
            [
                "name" => "vehicle-services.update",
                "module" => "vehicle-services",
                "action" => "update",
                "alias" => "update_vehicle_services"
            ],
            [
                "name" => "vehicle-services.update-status",
                "module" => "vehicle-services",
                "action" => "update",
                "alias" => "update_vehicle_services_status"
            ],

            // Clients
            [
                "name" => "clients.get",
                "module" => "clients",
                "action" => "get",
                "alias" => "get_clients"
            ],
            [
                "name" => "clients.create",
                "module" => "clients",
                "action" => "create",
                "alias" => "create_clients"
            ],
            [
                "name" => "clients.update",
                "module" => "clients",
                "action" => "update",
                "alias" => "update_clients"
            ],
            [
                "name" => "clients.update-status",
                "module" => "clients",
                "action" => "update",
                "alias" => "update_clients_status"
            ],

            // Clients Vehicles
            [
                "name" => "clients-vehicles.get",
                "module" => "clients-vehicles",
                "action" => "get",
                "alias" => "get_clients_vehicles"
            ],
            [
                "name" => "clients-vehicles.create",
                "module" => "clients-vehicles",
                "action" => "create",
                "alias" => "create_clients_vehicles"
            ],
            [
                "name" => "clients-vehicles.update",
                "module" => "clients-vehicles",
                "action" => "update",
                "alias" => "update_clients_vehicles"
            ],
            [
                "name" => "clients-vehicles.update-status",
                "module" => "clients-vehicles",
                "action" => "update",
                "alias" => "update_clients_vehicles_status"
            ],

            // Client Vehicles Service
            [
                "name" => "clients-vehicles-services.get",
                "module" => "clients-vehicles-services",
                "action" => "get",
                "alias" => "get_clients_vehicles_services"
            ],
            [
                "name" => "clients-vehicles-services.create",
                "module" => "clients-vehicles-services",
                "action" => "create",
                "alias" => "create_clients_vehicles_services"
            ],
            [
                "name" => "clients-vehicles-services.update",
                "module" => "clients-vehicles-services",
                "action" => "update",
                "alias" => "update_clients_vehicles_services"
            ],
            [
                "name" => "clients-vehicles-services.update-status",
                "module" => "clients-vehicles-services",
                "action" => "update",
                "alias" => "update_clients_vehicles_services_status"
            ],

            // Appointments
            [
                "name" => "appointments.get",
                "module" => "appointments",
                "action" => "get",
                "alias" => "get_appointments"
            ],
            [
                "name" => "appointments.update",
                "module" => "appointments",
                "action" => "update",
                "alias" => "update_appointments"
            ],
            [
                "name" => "appointments.update-status",
                "module" => "appointments",
                "action" => "update",
                "alias" => "update_appointments_status"
            ],

            // Tickets
            [
                "name" => "tickets.get",
                "module" => "tickets",
                "action" => "get",
                "alias" => "get_tickets"
            ],
            [
                "name" => "tickets.respond",
                "module" => "tickets",
                "action" => "respond",
                "alias" => "respond_tickets"
            ],
            [
                "name" => "tickets.update-status",
                "module" => "tickets",
                "action" => "update",
                "alias" => "update_tickets_status"
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission["name"], 'guard_name' => 'admin', 'module' => $permission['module'], 'action' => $permission['action'], 'alias' => $permission['alias']]);
        }

        $admin = Role::create(['name' => 'admin', 'guard_name' => 'admin', 'alias' => 'admin']);
        $admin->givePermissionTo(Permission::all());

    }
}
