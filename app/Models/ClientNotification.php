<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientNotification extends Model
{
    protected $table = 'client_notifications';

    protected $fillable = [
        'client_id',
        'title',
        'message',
        'payload',
        'read',
        'status',
        'read_at'
    ];

    protected $casts = [
        'payload' => 'array',
        'created_at' => 'date:Y-m-d H:i:s',
        'updated_at' => 'date:Y-m-d H:i:s',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
