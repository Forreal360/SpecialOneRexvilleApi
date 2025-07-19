<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModel extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vpic_id',
        'make_id',
    ];

    /**
     * Get the make that owns this model.
     */
    public function make()
    {
        return $this->belongsTo(VehicleMake::class, 'make_id');
    }

    /**
     * Get the years for this model.
     */
    public function years()
    {
        return $this->hasMany(VehicleModelYear::class, 'model_id');
    }
}
