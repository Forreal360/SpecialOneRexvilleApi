<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Actions\V1\Ticket\CreateTicketAction;
use App\Actions\V1\Ticket\CreateMessageAction;
use App\Actions\V1\Ticket\ListTicketsAction;
use App\Actions\V1\Ticket\GetTicketAction;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    public function __construct(
        private CreateTicketAction $createTicketAction,
        private CreateMessageAction $createMessageAction,
        private ListTicketsAction $listTicketsAction,
        private GetTicketAction $getTicketAction
    ) {}

    /**
     * Get all tickets for the authenticated client
     */
    public function index(Request $request): JsonResponse
    {
        $result = $this->listTicketsAction->execute($request->all());

        return response()->json($result->toArray(), $result->getStatusCode());
    }

    /**
     * Create a new ticket
     */
    public function store(Request $request): JsonResponse
    {
        $result = $this->createTicketAction->execute($request->all());

        return response()->json($result->toArray(), $result->getStatusCode());
    }

    /**
     * Get a specific ticket with messages
     */
    public function show(Request $request, int $id): JsonResponse
    {
        $data = array_merge($request->all(), ['id' => $id]);
        $result = $this->getTicketAction->execute($data);

        return response()->json($result->toArray(), $result->getStatusCode());
    }

    /**
     * Create a message in an existing ticket
     */
    public function storeMessage(Request $request): JsonResponse
    {
        $result = $this->createMessageAction->execute($request->all());

        return response()->json($result->toArray(), $result->getStatusCode());
    }
}
