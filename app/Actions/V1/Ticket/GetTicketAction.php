<?php

declare(strict_types=1);

namespace App\Actions\V1\Ticket;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\Ticket\TicketService;
use App\Http\Resources\V1\TicketResource;

class GetTicketAction extends Action
{
    public function __construct(private TicketService $ticketService) {}

    public function handle($data): ActionResult
    {
        $validatedData = $this->validateData($data, [
            'id' => 'required|integer|exists:tickets,id',
        ]);

        $clientId = auth()->user()->id;

        // Get the ticket with messages ensuring it belongs to the client
        $ticket = $this->ticketService->getClientTicket(
            (int)$validatedData['id'],
            (string)$clientId
        );

        if (!$ticket) {
            return $this->errorResult(
                message: 'Ticket no encontrado o no tienes permisos para acceder a él.',
                statusCode: 404
            );
        }

        return $this->successResult(
            data: new TicketResource($ticket),
            message: 'Ticket obtenido exitosamente.'
        );
    }
}
