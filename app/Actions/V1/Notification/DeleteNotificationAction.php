<?php

declare(strict_types=1);

namespace App\Actions\V1\Notification;

use App\Actions\V1\Action;
use App\Models\ClientNotification;
use App\Support\ActionResult;
use Illuminate\Support\Facades\DB;
use App\Exceptions\ValidationErrorException;

class DeleteNotificationAction extends Action
{
    public function __construct(
        private readonly ClientNotification $notification
    ) {
    }

    public function handle($data): ActionResult
    {
        return DB::transaction(function () use ($data) {

            $validatedData = $this->validateData($data, [
                'notification_id' => 'required'
            ]);

            $notification = $this->notification->find($validatedData['notification_id']);

            if (!$notification) {
                abort(404, trans('validation.not_found'));
            }

            // Verificar que la notificación pertenece al cliente autenticado
            if ($notification->client_id !== auth()->user()->id) {
                throw new ValidationErrorException([
                    'notification_id' => [trans('validation.no_permissions')]
                ]);
            }

            // Marcar como eliminada (status T)
            $notification->update([
                'status' => 'T'
            ]);

            return $this->successResult();
        });
    }
}
