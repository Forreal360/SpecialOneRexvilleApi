<?php

declare(strict_types=1);

namespace App\Actions\V1\Ticket;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\Ticket\TicketService;
use App\Services\V1\Ticket\MessageService;
use App\Http\Resources\V1\TicketMessageResource;

class CreateMessageAction extends Action
{
    public function __construct(
        private TicketService $ticketService,
        private MessageService $messageService
    ) {}

    public function handle($data): ActionResult
    {
        $validatedData = $this->validateData($data, [
            'ticket_id' => 'required|integer|exists:tickets,id',
            'message' => 'required|string|max:1000',
        ]);

        $clientId = auth()->user()->id;

        // Verify that the ticket belongs to the authenticated client
        $ticket = $this->ticketService->getClientTicket(
            (int)$validatedData['ticket_id'],
            (string)$clientId
        );

        if (!$ticket) {
            return $this->errorResult(
                message: 'Ticket no encontrado o no tienes permisos para acceder a él.',
                statusCode: 404
            );
        }

        // Check if ticket is closed
        if ($ticket->status === 'closed') {
            return $this->errorResult(
                message: 'No puedes enviar mensajes en un ticket cerrado.',
                statusCode: 400
            );
        }
        // Create the message
        $message = $this->messageService->createClientMessage([
            'ticket_id' => $validatedData['ticket_id'],
            'client_id' => $clientId,
            'message' => $validatedData['message'],
            'message_type' => 'text',
        ]);

        // Load the fromeable relationship
        $message->load('fromeable');

        return $this->successResult();
    }
}
