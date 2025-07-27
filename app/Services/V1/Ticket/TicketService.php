<?php

declare(strict_types=1);

namespace App\Services\V1\Ticket;

use App\Models\Ticket;
use App\Services\V1\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TicketService extends Service
{
    protected string $modelClass = Ticket::class;


    protected array $searchableFields = ['subject'];

    /**
     * Create a new ticket for a client
     *
     * @param array $data
     * @return Ticket
     */
    public function createTicket(array $data): Ticket
    {
        return $this->create([
            'subject' => $data['subject'],
            'client_id' => $data['client_id'],
            'status' => 'open',
        ]);
    }

    /**
     * Get tickets for a specific client
     *
     * @param string $clientId
     * @param array $filters
     * @param int $page
     * @param string $search
     * @param string $sort_by
     * @param string $sort_direction
     * @param int|null $per_page
     * @return LengthAwarePaginator
     */
    public function getClientTickets(
        string $clientId,
        array $filters = [],
        int $page = 1,
        string $search = '',
        string $sort_by = 'created_at',
        string $sort_direction = 'desc',
        ?int $per_page = null
    ): LengthAwarePaginator {
        $filters['client_id'] = $clientId;

        return $this->getPaginated(
            $filters,
            $page,
            $search,
            $sort_by,
            $sort_direction,
            $per_page
        );
    }

    /**
     * Get ticket with messages
     *
     * @param int $ticketId
     * @return Ticket|null
     */
    public function getTicketWithMessages(int $ticketId): ?Ticket
    {
        return Ticket::with(['messages.fromeable', 'client'])
            ->find($ticketId);
    }

    /**
     * Get tickets by status
     *
     * @param string $status
     * @return Collection
     */
    public function getTicketsByStatus(string $status): Collection
    {
        return Ticket::where('status', $status)->get();
    }

    /**
     * Update ticket status
     *
     * @param int $ticketId
     * @param string $status
     * @return bool
     */
    public function updateTicketStatus(int $ticketId, string $status): bool
    {
        return Ticket::where('id', $ticketId)->update(['status' => $status]) > 0;
    }

    /**
     * Close ticket
     *
     * @param int $ticketId
     * @return bool
     */
    public function closeTicket(int $ticketId): bool
    {
        return $this->updateTicketStatus($ticketId, 'closed');
    }

    /**
     * Get all tickets for a specific client (without pagination)
     *
     * @param string $clientId
     * @param array $filters
     * @param string $search
     * @param string $sort_by
     * @param string $sort_direction
     * @return Collection
     */
    public function getAllClientTickets(
        string $clientId,
        array $filters = [],
        string $search = '',
        string $sort_by = 'created_at',
        string $sort_direction = 'desc'
    ): Collection {
        $query = Ticket::query()
            ->with(['latestMessage'])
            ->where('client_id', $clientId);

        // Apply search
        if (!empty($search)) {
            $query->where('subject', 'like', '%' . $search . '%');
        }

        // Apply filters
        foreach ($filters as $field => $value) {
            if (!empty($value) && $field !== 'client_id') {
                if (is_array($value)) {
                    $query->whereIn($field, $value);
                } else {
                    $query->where($field, $value);
                }
            }
        }

        // Apply sorting
        $query->orderBy($sort_by, $sort_direction);

        return $query->get();
    }

    /**
     * Get client ticket by ID
     *
     * @param int $ticketId
     * @param string $clientId
     * @return Ticket|null
     */
    public function getClientTicket(int $ticketId, string $clientId): ?Ticket
    {

        $ticket = $this->findByIdOrFail($ticketId);
        $ticket->new_message_from_client = 'N';
        $ticket->save();

        return Ticket::where('id', $ticketId)
            ->where('client_id', $clientId)
            ->with(['messages.fromeable', 'client'])
            ->first();
    }
}
