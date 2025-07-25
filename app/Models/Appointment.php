<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Appointment extends Model
{
    protected $fillable = [
        'client_id',
        'vehicle_id',
        'service_id',
        'appointment_datetime',
        'timezone',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_datetime' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(ClientVehicle::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(VehicleService::class);
    }
}
