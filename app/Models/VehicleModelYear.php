<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleModelYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'vpic_id',
        'model_id',
    ];

    /**
     * Get the model that owns this year.
     */
    public function model()
    {
        return $this->belongsTo(VehicleModel::class, 'model_id');
    }
}
