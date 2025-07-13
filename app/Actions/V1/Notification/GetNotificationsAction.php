<?php

declare(strict_types=1);

namespace App\Actions\V1\Notification;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ClientNotificationService;
use App\Http\Resources\V1\NotificationResource;

class GetNotificationsAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(private ClientNotificationService $clientNotificationService)
    {
        // Inject services here
        // Example: $this->service = $service;
    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {
        $user = auth()->user();

        $pagination = $this->clientNotificationService->getPaginated(
            filters: [
                'client_id' => $user->id,
                'status' => 'A' // Solo notificaciones activas (no eliminadas)
            ],
            per_page: (int) ($data['per_page'] ?? 10),
            page: (int)( $data['page'] ?? 1 ),
            sort_by: $data['sort_by'] ?? 'created_at',
            sort_direction: $data['sort_direction'] ?? 'desc'
        );

        $pagination = generatePaginationResource($pagination, NotificationResource::class, "notifications");

        return $this->successResult(
            data: $pagination
        );
    }
}
