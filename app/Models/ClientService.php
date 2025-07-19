<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientService extends Model
{

    protected $table = 'client_services';

    protected $fillable = [
        'client_id',
        'vehicle_id',
        'date',
        'name',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(ClientVehicle::class);
    }
}
