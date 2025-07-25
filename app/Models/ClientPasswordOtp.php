<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class ClientPasswordOtp extends Model
{
    /**
     * The table associated with the model.
     */
    protected $table = 'client_password_otp';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'email',
        'otp_code',
        'expires_at',
        'is_used',
        'attempts'
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
        'attempts' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relación con el modelo Client
     */
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'email', 'email');
    }

    /**
     * Scope para obtener códigos válidos (no usados y no expirados)
     */
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }

    /**
     * Scope para obtener códigos por email
     */
    public function scopeByEmail($query, string $email)
    {
        return $query->where('email', $email);
    }

    /**
     * Verificar si el código está expirado
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Verificar si el código ya fue usado
     */
    public function isUsed(): bool
    {
        return $this->is_used;
    }

    /**
     * Verificar si el código es válido (no usado y no expirado)
     */
    public function isValid(): bool
    {
        return !$this->isUsed() && !$this->isExpired();
    }

    /**
     * Marcar el código como usado
     */
    public function markAsUsed(): bool
    {
        $this->is_used = true;
        return $this->save();
    }

    /**
     * Incrementar los intentos de verificación
     */
    public function incrementAttempts(): bool
    {
        $this->attempts += 1;
        return $this->save();
    }

    /**
     * Generar un código OTP aleatorio de 6 dígitos
     */
    public static function generateOtpCode(): string
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Crear un nuevo código OTP para un email
     */
    public static function createForEmail(string $email, int $expirationMinutes = 15): self
    {
        // Invalidar códigos anteriores del mismo email
        self::where('email', $email)->update(['is_used' => true]);

        return self::create([
            'email' => $email,
            'otp_code' => self::generateOtpCode(),
            'expires_at' => now()->addMinutes($expirationMinutes),
            'is_used' => false,
            'attempts' => 0
        ]);
    }
}
