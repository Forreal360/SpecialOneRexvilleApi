<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ClientService;

class RefreshFcmTokenAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(
        private ClientService $clientService
    ){

    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {
        // Validate input data
        $validated = $this->validateData($data, [
            'fcm_token' => 'required|string',
            'os' => 'required|string|in:android,ios,web',
        ]);

        $user = auth()->user();

        // Update FCM token for current token
        $this->clientService->refreshFcmToken($user, $validated['fcm_token'], $validated['os']);

        return $this->successResult();
    }
}
