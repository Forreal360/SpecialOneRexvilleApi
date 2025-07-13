<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Http\Resources\V1\ClientResource;
use App\Support\ActionResult;
use App\Services\V1\ClientService;

class RefreshTokenAction extends Action
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

        // Revoke current token and create a new one
        $newToken = $this->clientService->refreshAuthToken($user, $validated['fcm_token'], $validated['os']);

        return $this->successResult(
            data: [
                "token" => $newToken,
                "client" => new ClientResource($user)
            ]
        );
    }
}
