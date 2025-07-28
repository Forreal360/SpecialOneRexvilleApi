<?php

declare(strict_types=1);

namespace App\Services\V1\Ticket;

use App\Models\TicketMessage;
use App\Models\Client;
use App\Models\Admin;
use App\Services\V1\Service;
use App\Services\V1\Ticket\TicketService;
use Illuminate\Support\Collection;

class MessageService extends Service
{
    protected string $modelClass = TicketMessage::class;

    public function __construct(
        private TicketService $ticketService
    ) {}

    /**
     * Create a message from a client
     *
     * @param array $data
     * @return TicketMessage
     */
    public function createClientMessage(array $data): TicketMessage
    {

        $ticket = $this->ticketService->findByIdOrFail($data['ticket_id']);
        $ticket->new_message_from_client = 'Y';
        $ticket->new_message_from_support = 'N';
        $ticket->save();

        return $this->create([
            'ticket_id' => $data['ticket_id'],
            'fromeable_type' => "client",
            'fromeable_id' => $data['client_id'],
            'message' => $data['message'],
            'message_type' => $data['message_type'] ?? 'text',
            'file_path' => $data['file_path'] ?? null,
        ]);
    }

    /**
     * Create a message from an admin
     *
     * @param array $data
     * @return TicketMessage
     */
    public function createAdminMessage(array $data): TicketMessage
    {
        return $this->create([
            'ticket_id' => $data['ticket_id'],
            'fromeable_type' => Admin::class,
            'fromeable_id' => $data['admin_id'],
            'message' => $data['message'],
            'message_type' => $data['message_type'] ?? 'text',
            'file_path' => $data['file_path'] ?? null,
        ]);
    }

    /**
     * Get messages for a specific ticket
     *
     * @param int $ticketId
     * @return Collection
     */
    public function getTicketMessages(int $ticketId): Collection
    {
        return TicketMessage::where('ticket_id', $ticketId)
            ->with('fromeable')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get messages from clients for a ticket
     *
     * @param int $ticketId
     * @return Collection
     */
    public function getClientMessages(int $ticketId): Collection
    {
        return TicketMessage::where('ticket_id', $ticketId)
            ->fromClients()
            ->with('fromeable')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get messages from admins for a ticket
     *
     * @param int $ticketId
     * @return Collection
     */
    public function getAdminMessages(int $ticketId): Collection
    {
        return TicketMessage::where('ticket_id', $ticketId)
            ->fromAdmins()
            ->with('fromeable')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    /**
     * Get latest message for a ticket
     *
     * @param int $ticketId
     * @return TicketMessage|null
     */
    public function getLatestMessage(int $ticketId): ?TicketMessage
    {
        return TicketMessage::where('ticket_id', $ticketId)
            ->with('fromeable')
            ->latest()
            ->first();
    }

    /**
     * Count messages for a ticket
     *
     * @param int $ticketId
     * @return int
     */
    public function countTicketMessages(int $ticketId): int
    {
        return TicketMessage::where('ticket_id', $ticketId)->count();
    }

    /**
     * Get text messages only for a ticket
     *
     * @param int $ticketId
     * @return Collection
     */
    public function getTextMessages(int $ticketId): Collection
    {
        return TicketMessage::where('ticket_id', $ticketId)
            ->textMessages()
            ->with('fromeable')
            ->orderBy('created_at', 'asc')
            ->get();
    }
}
