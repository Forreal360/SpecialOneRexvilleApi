<?php

declare(strict_types=1);

namespace App\Actions\V1\Ticket;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\Ticket\TicketService;
use App\Services\V1\Ticket\MessageService;
use App\Http\Resources\V1\TicketResource;
use App\Jobs\AdminNotificationJob;
use Illuminate\Support\Facades\DB;

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

        return DB::transaction(function () use ($validatedData, $clientId) {
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

            // Disparar notificación admin después de crear el ticket
            $this->dispatchAdminNotification($ticketWithMessages, $validatedData['message']);

            return $this->successResult(
                data: new TicketResource($ticketWithMessages),
                message: 'Ticket creado exitosamente. Se ha notificado al administrador.',
                statusCode: 201
            );
        });
    }

    private function dispatchAdminNotification($ticket, string $initialMessage): void
    {
        try {
            // Cargar relaciones necesarias
            $ticket->load(['client']);

            // Obtener todos los administradores activos
            $adminIds = \App\Models\Admin::where('status', 'A')->pluck('id');

            foreach ($adminIds as $adminId) {
                // Determinar prioridad basada en palabras clave
                $priority = $this->determinePriority($ticket->subject, $initialMessage);
                $priorityEmoji = $this->getPriorityEmoji($priority);

                // Título y mensaje de la notificación
                $title = "Nuevo Ticket de Soporte";
                $message = "Se recibió un nuevo ticket de soporte: {$ticket->subject}";

                // Preview del mensaje inicial (máximo 100 caracteres)
                $messagePreview = strlen($initialMessage) > 100 
                    ? substr($initialMessage, 0, 100) . '...' 
                    : $initialMessage;

                // Payload con información detallada
                $payload = [
                    'type' => 'ticket_created',
                    'ticket_id' => $ticket->id,
                    'client' => [
                        'id' => $ticket->client->id,
                        'name' => $ticket->client->name,
                        'email' => $ticket->client->email,
                        'phone' => $ticket->client->phone,
                    ],
                    'subject' => $ticket->subject,
                    'message_preview' => $messagePreview,
                    'priority' => $priority,
                    'status' => $ticket->status,
                    'created_at' => $ticket->created_at->format('d/m/Y H:i'),
                    'route' => "/tickets/{$ticket->id}" // Ruta para el dashboard
                ];

                // Disparar el job de notificación admin
                AdminNotificationJob::dispatch(
                    admin_id: $adminId,
                    title: $title,
                    message: $message,
                    action: 'redirect',
                    payload: $payload,
                    fcm_tokens: [], // Se pueden agregar tokens FCM si están disponibles
                    send_push: $priority === 'high' || $priority === 'urgent' // Push solo para prioridades altas
                );
            }
        } catch (\Exception $e) {
            // Log del error pero no fallar la creación del ticket
            \Log::error('Error dispatching admin notification for ticket: ' . $e->getMessage());
        }
    }

    private function determinePriority(string $subject, string $message): string
    {
        $content = strtolower($subject . ' ' . $message);
        
        // Palabras clave para prioridad urgente
        $urgentKeywords = ['urgente', 'emergencia', 'no funciona', 'no arranca', 'accidente', 'crítico'];
        
        // Palabras clave para prioridad alta
        $highKeywords = ['problema', 'falla', 'error', 'roto', 'avería', 'reparación', 'inmediato'];
        
        // Palabras clave para prioridad media
        $mediumKeywords = ['mantenimiento', 'revisión', 'consulta', 'duda', 'información'];

        foreach ($urgentKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return 'urgent';
            }
        }

        foreach ($highKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return 'high';
            }
        }

        foreach ($mediumKeywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
                return 'medium';
            }
        }

        return 'low';
    }

    private function getPriorityEmoji(string $priority): string
    {
        return match ($priority) {
            'urgent' => '🚨',
            'high' => '⚠️',
            'medium' => '📋',
            'low' => '💬',
            default => '📋'
        };
    }
}
