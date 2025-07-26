<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Client extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'clients';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'phone_code',
        'phone',
        'license_number',
        'profile_photo',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];



    public function vehicles()
    {
        return $this->hasMany(ClientVehicle::class);
    }

    public function services()
    {
        return $this->hasMany(ClientService::class);
    }

    /**
     * Get the social accounts for the client.
     */
    public function socialAccounts()
    {
        return $this->hasMany(SocialAccount::class);
    }

    public function profilePhoto(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value == null ? null : Storage::disk('s3')->temporaryUrl($value, Carbon::now()->addMinutes(120)),
            set: fn ($value) => $value == null ? null : Storage::disk('s3')->put('/clients', $value),
        );
    }
    
}
