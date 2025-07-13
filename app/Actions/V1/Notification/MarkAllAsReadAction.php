<?php

declare(strict_types=1);

namespace App\Actions\V1\Notification;

use App\Actions\V1\Action;
use App\Models\ClientNotification;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ValidationErrorException;

class MarkAllAsReadAction extends Action
{
    public function __construct(
        private readonly ClientNotification $notification
    ) {
    }

    public function handle($data): ActionResult
    {
        return DB::transaction(function () use ($data) {
            $user = auth()->user();

            // Obtener todas las notificaciones no leídas del cliente
            $unreadNotifications = $this->notification
                ->where('client_id', $user->id)
                ->where('read', 'N')
                ->where('status', 'A')
                ->get();

            if ($unreadNotifications->isEmpty()) {
                return $this->successResult();
            }

            // Marcar todas como leídas
            $this->notification
                ->where('client_id', $user->id)
                ->where('read', 'N')
                ->where('status', 'A')
                ->update([
                    'read' => 'Y',
                    'read_at' => now()->format('Y-m-d H:i:s')
                ]);

            return $this->successResult();
        });
    }
}
