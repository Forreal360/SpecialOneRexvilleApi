<?php

namespace App\Jobs;

use App\Services\V1\AdminNotificationService;
use App\Services\V1\FirebaseService;
use App\Services\V1\FirestoreService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class AdminNotificationJob implements ShouldQueue
{
    use Queueable;

    public $admin_id;
    public $title;
    public $message;
    public $action;
    public $payload;
    public $fcm_tokens;
    public $send_push;

    public function __construct(
        $admin_id,
        $title,
        $message,
        $action = 'none',
        $payload = [],
        $fcm_tokens = [],
        $send_push = false
    ) {
        $this->admin_id = $admin_id;
        $this->title = $title;
        $this->message = $message;
        $this->action = $action;
        $this->payload = $payload;
        $this->fcm_tokens = $fcm_tokens;
        $this->send_push = $send_push;
    }

    public function handle(
        AdminNotificationService $adminNotificationService,
        FirebaseService $firebaseService,
        FirestoreService $firestoreService
    ): void {
        try {
            $notification = $adminNotificationService->createNotification(
                admin_id: $this->admin_id,
                title: $this->title,
                message: $this->message,
                action: $this->action,
                payload: $this->payload
            );

            // Small delay to ensure database commit before Firestore update
            sleep(1);

            $firestoreService->updateFirestoreDocument(
                'admin-notification-trigger',
                (string) $this->admin_id,
                [
                    'notification_id' => $notification->id,
                    'updated_at' => now()->toISOString()
                ]
            );

            if ($this->send_push && !empty($this->fcm_tokens)) {
                $firebaseService->sendPushFcm(
                    $this->fcm_tokens,
                    [
                        'title' => $this->title,
                        'body' => $this->message
                    ],
                    [
                        'notification_id' => $notification->id,
                        'action' => $this->action,
                        'payload' => json_encode($this->payload)
                    ]
                );
            }

        } catch (\Throwable $th) {
            Log::error("Error procesando notificación para admin {$this->admin_id}: " . $th->getMessage());
            Log::error($th->getTraceAsString());
        }
    }
}