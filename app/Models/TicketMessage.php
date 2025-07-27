<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class TicketMessage extends Model
{
    use HasFactory;

    protected $table = 'ticket_messages';

    protected $fillable = [
        'ticket_id',
        'fromeable_type',
        'fromeable_id',
        'message',
        'message_type',
        'file_path',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the ticket that owns the message.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the owning fromeable model (Client or Admin).
     */
    public function fromeable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope to get messages from clients
     */
    public function scopeFromClients($query)
    {
        return $query->where('fromeable_type', Client::class);
    }

    /**
     * Scope to get messages from admins
     */
    public function scopeFromAdmins($query)
    {
        return $query->where('fromeable_type', Admin::class);
    }

    /**
     * Scope to get text messages only
     */
    public function scopeTextMessages($query)
    {
        return $query->where('message_type', 'text');
    }

    /**
     * Check if message is from client
     */
    public function isFromClient(): bool
    {
        return $this->fromeable_type === Client::class;
    }

    /**
     * Check if message is from admin
     */
    public function isFromAdmin(): bool
    {
        return $this->fromeable_type === Admin::class;
    }
}
