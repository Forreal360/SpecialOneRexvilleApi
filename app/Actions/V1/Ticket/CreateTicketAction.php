<?php

declare(strict_types=1);

namespace App\Actions\V1\Ticket;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\Ticket\TicketService;
use App\Services\V1\Ticket\MessageService;
use App\Http\Resources\V1\TicketResource;

class CreateTicketAction extends Action
{
    public function __construct(
        private TicketService $ticketService,
        private MessageService $messageService
    ) {}

    public function handle($data): ActionResult
    {
        $validatedData = $this->validateData($data, [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Add client_id from authenticated user
        $clientId = auth()->user()->id;

        // Create the ticket
        $ticket = $this->ticketService->createTicket([
            'subject' => $validatedData['subject'],
            'client_id' => $clientId,
        ]);

        // Create the initial message
        $this->messageService->createClientMessage([
            'ticket_id' => $ticket->id,
            'client_id' => $clientId,
            'message' => $validatedData['message'],
            'message_type' => 'text',
        ]);

        // Get the ticket with messages for the response
        $ticketWithMessages = $this->ticketService->getTicketWithMessages($ticket->id);

        return $this->successResult(
            data: new TicketResource($ticketWithMessages),
            message: 'Ticket creado exitosamente.',
            statusCode: 201
        );
    }
}
