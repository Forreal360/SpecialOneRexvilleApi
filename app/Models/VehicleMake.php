<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleMake extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vpic_id',
    ];

    /**
     * Get the models for this make.
     */
    public function models()
    {
        return $this->hasMany(VehicleModel::class, 'make_id');
    }
}
