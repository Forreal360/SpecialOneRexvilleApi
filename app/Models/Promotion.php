<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_date',
        'end_date',
        'image_path',
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

    public function imagePath(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == null ? null : Storage::disk('s3')->temporaryUrl($value, Carbon::now()->addMinutes(120))
        );
    }

}
