<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientVehicle extends Model
{
    protected $table = 'client_vehicles';

    protected $fillable = [
        'client_id',
        'year',
        'make_id',
        'model_id',
        'vin',
        'buy_date',
        'insurance',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function make(): BelongsTo
    {
        return $this->belongsTo(VehicleMake::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function services(): HasMany
    {
        return $this->hasMany(ClientService::class);
    }
}
