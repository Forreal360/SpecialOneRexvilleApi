<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Http\Resources\V1\ClientResource;
use App\Support\ActionResult;
use App\Exceptions\ValidationErrorException;
use App\Services\V1\ClientService;
use Illuminate\Support\Facades\Hash;

class LoginWithEmailAction extends Action
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
            'email' => 'required|email',
            'password' => 'required|string',
            'fcm_token' => 'required|string',
            'os' => 'required|string|in:android,ios,web',
        ]);

        $user = $this->clientService->findBy('email', $validated['email']);

        if($user === null) {
            throw new ValidationErrorException(errors: [
                "email" => [trans('auth.failed')]
            ]);
        }

        if ($user->status !== 'A') {
            throw new ValidationErrorException(
                errors: [
                    "email" => [trans('auth.failed')]
                ]
            );
        }

        if ($user === null) {
            throw new ValidationErrorException(errors: [
                "email" => [trans('auth.failed')]
            ]);
        }

        if (!Hash::check($validated['password'], $user->password)) {
            throw new ValidationErrorException(
                errors: [
                    "password" => [trans('auth.password')]
                ],
            );
        }

        $token = $this->clientService->saveAuthToken($user, $validated['fcm_token'], $validated['os']);

        return $this->successResult(
            data: [
                "token" => $token,
                "client" => new ClientResource($user)
            ]
        );
    }
}
