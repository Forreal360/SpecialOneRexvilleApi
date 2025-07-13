<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialAccount extends Model
{
    use HasFactory;

    protected $table = 'client_social_accounts';

    protected $fillable = [
        'client_id',
        'provider',
        'provider_user_id',
        'email',
        'name',
        'avatar',
        'provider_data',
    ];

    protected $casts = [
        'provider_data' => 'array',
    ];

    /**
     * Get the client that owns the social account.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
