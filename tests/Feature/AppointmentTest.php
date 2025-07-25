<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Client;
use App\Models\ClientVehicle;
use App\Models\VehicleService;
use App\Models\Appointment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AppointmentTest extends TestCase
{
    use RefreshDatabase;

    protected Client $client;
    protected ClientVehicle $vehicle;
    protected VehicleService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test data
        $this->client = Client::factory()->create();
        $this->vehicle = ClientVehicle::factory()->create(['client_id' => $this->client->id]);
        $this->service = VehicleService::factory()->create();
    }

    public function test_can_create_appointment()
    {
        $appointmentData = [
            'vehicle_id' => $this->vehicle->id,
            'service_id' => $this->service->id,
            'appointment_datetime' => now()->addDays(2)->setTime(14, 30)->format('Y-m-d H:i:s'),
            'timezone' => 'America/Mexico_City',
            'notes' => 'Test appointment',
        ];

        $response = $this->actingAs($this->client)
            ->postJson('/api/v1/appointments', $appointmentData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'id',
                    'client_id',
                    'vehicle_id',
                    'service_id',
                    'appointment_date',
                    'appointment_time',
                    'status',
                    'notes',
                    'vehicle',
                    'service',
                ]
            ]);

        $this->assertDatabaseHas('appointments', [
            'client_id' => $this->client->id,
            'vehicle_id' => $this->vehicle->id,
            'service_id' => $this->service->id,
            'timezone' => $appointmentData['timezone'],
        ]);
    }

    public function test_can_list_appointments()
    {
        // Create some test appointments
        Appointment::create([
            'client_id' => $this->client->id,
            'vehicle_id' => $this->vehicle->id,
            'service_id' => $this->service->id,
            'appointment_datetime' => now()->addDays(1)->setTime(9, 0),
            'timezone' => 'America/Mexico_City',
            'status' => 'pending',
        ]);

        $response = $this->actingAs($this->client)
            ->getJson('/api/v1/appointments');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    '*' => [
                        'id',
                        'client_id',
                        'vehicle_id',
                        'service_id',
                        'appointment_date',
                        'appointment_time',
                        'status',
                        'vehicle',
                        'service',
                    ]
                ]
            ]);
    }

    public function test_cannot_create_appointment_with_invalid_vehicle()
    {
        $appointmentData = [
            'vehicle_id' => 999, // Non-existent vehicle
            'service_id' => $this->service->id,
            'appointment_datetime' => now()->addDays(2)->setTime(14, 30)->format('Y-m-d H:i:s'),
            'timezone' => 'America/Mexico_City',
        ];

        $response = $this->actingAs($this->client)
            ->postJson('/api/v1/appointments', $appointmentData);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'message',
                'errors'
            ]);
    }

    public function test_cannot_create_appointment_with_past_date()
    {
        $appointmentData = [
            'vehicle_id' => $this->vehicle->id,
            'service_id' => $this->service->id,
            'appointment_datetime' => now()->subDays(1)->setTime(14, 30)->format('Y-m-d H:i:s'),
            'timezone' => 'America/Mexico_City',
        ];

        $response = $this->actingAs($this->client)
            ->postJson('/api/v1/appointments', $appointmentData);

        $response->assertStatus(400)
            ->assertJsonStructure([
                'success',
                'message',
                'errors'
            ]);
    }

    public function test_can_get_timezones()
    {
        $response = $this->actingAs($this->client)
            ->getJson('/api/v1/timezones');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data'
            ])
            ->assertJson([
                'success' => true,
                'data' => [
                    'America/Mexico_City' => 'Ciudad de México (UTC-6)',
                    'UTC' => 'UTC (UTC+0)',
                ]
            ]);
    }
}
