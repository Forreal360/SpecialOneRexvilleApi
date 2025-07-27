<?php

declare(strict_types=1);

namespace App\Actions\V1\Ticket;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\Ticket\TicketService;
use App\Http\Resources\V1\TicketResource;

class ListTicketsAction extends Action
{
    public function __construct(private TicketService $ticketService) {}

    public function handle($data): ActionResult
    {
        $validatedData = $this->validateData($data, [
            'search' => 'nullable|string|max:255',
            'status' => 'nullable|string|in:open,in_progress,closed',
            'sort_by' => 'nullable|string|in:id,subject,status,created_at,updated_at',
            'sort_direction' => 'nullable|string|in:asc,desc',
        ]);

        $clientId = auth()->user()->id;

        $filters = [];
        if (!empty($validatedData['status'])) {
            $filters['status'] = $validatedData['status'];
        }

        $tickets = $this->ticketService->getAllClientTickets(
            (string)$clientId,
            $filters,
            $validatedData['search'] ?? '',
            $validatedData['sort_by'] ?? 'created_at',
            $validatedData['sort_direction'] ?? 'desc'
        );

        return $this->successResult(
            data: TicketResource::collection($tickets),
            message: 'Tickets obtenidos exitosamente.'
        );
    }
}
