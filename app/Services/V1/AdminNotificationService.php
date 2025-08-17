<?php

namespace App\Services\V1;

use App\Services\V1\Service;
use App\Models\AdminNotification;

class AdminNotificationService extends Service
{
    public function __construct()
    {
        $this->model = AdminNotification::class;
        $this->searchableFields = ['title', 'message'];
        $this->per_page = 20;
    }

    /**
     * Crear una nueva notificación admin
     */
    public function createNotification(
        int $admin_id, 
        string $title, 
        string $message, 
        string $action = 'none', 
        array $payload = []
    ): AdminNotification {
        return AdminNotification::create([
            'admin_id' => $admin_id,
            'title' => $title,
            'message' => $message,
            'action' => $action,
            'payload' => json_encode($payload),
            'read' => 'N',
            'date' => now(),
        ]);
    }

    /**
     * Obtener notificaciones paginadas para un admin
     */
    public function getNotifications(int $admin_id, int $per_page = 20, int $current_page = 1)
    {
        return AdminNotification::where('admin_id', $admin_id)
            ->orderBy('date', 'desc')
            ->paginate($per_page, ['*'], 'page', $current_page);
    }

    /**
     * Marcar notificación como leída
     */
    public function markAsReadNotification(int $notification_id): bool
    {
        return AdminNotification::where('id', $notification_id)
            ->update(['read' => 'Y']);
    }

    /**
     * Marcar todas las notificaciones como leídas para un admin
     */
    public function markAsReadAllNotifications(int $admin_id): bool
    {
        return AdminNotification::where('admin_id', $admin_id)
            ->where('read', 'N')
            ->update(['read' => 'Y']);
    }

    /**
     * Obtener count de notificaciones no leídas
     */
    public function getUnreadNotificationsCount(int $admin_id): int
    {
        return AdminNotification::where('admin_id', $admin_id)
            ->where('read', 'N')
            ->count();
    }

    /**
     * Obtener una notificación específica
     */
    public function getNewNotification(int $notification_id): ?AdminNotification
    {
        return AdminNotification::find($notification_id);
    }
}