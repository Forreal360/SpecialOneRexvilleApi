<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleService extends Model
{

    protected $table = 'vehicle_services';

    protected $fillable = ['name', 'status'];

}
