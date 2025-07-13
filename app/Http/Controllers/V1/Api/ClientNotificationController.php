<?php

namespace App\Http\Controllers\V1\Api;

use App\Actions\V1\Notification\GetNotificationsAction;
use App\Actions\V1\Notification\MarkAsReadAction;
use App\Actions\V1\Notification\MarkAllAsReadAction;
use App\Actions\V1\Notification\DeleteNotificationAction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientNotificationController extends Controller
{

    public function getNotifications(Request $request, GetNotificationsAction $action  ): JsonResponse
    {
        $result = $action->execute($request->all());
        return $result->toApiResponse();
    }


    public function markAsRead(Request $request, int $notification_id, MarkAsReadAction $action): JsonResponse
    {
        $result = $action->execute(['notification_id' => $notification_id]);
        return $result->toApiResponse();
    }


    public function markAllAsRead(Request $request, MarkAllAsReadAction $action): JsonResponse
    {
        $result = $action->execute([]);
        return $result->toApiResponse();
    }


    public function delete(Request $request, int $notification_id, DeleteNotificationAction $action): JsonResponse
    {
        $result = $action->execute(['notification_id' => $notification_id]);
        return $result->toApiResponse();
    }
}
