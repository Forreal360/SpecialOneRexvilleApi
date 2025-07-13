<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'image_url',
        'redirect_url',
        'status',
    ];

    // Scope para promociones activas y válidas
    public function scopeActiveAndValid($query)
    {
        return $query->where('status', 'A')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    // Accessor para verificar si está activa y válida
    public function getIsValidAttribute()
    {
        return $this->status === 'A'
            && $this->start_date <= now()
            && $this->end_date >= now();
    }

}
