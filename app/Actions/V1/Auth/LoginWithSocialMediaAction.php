<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Http\Resources\V1\ClientResource;
use App\Support\ActionResult;
use App\Exceptions\ValidationErrorException;
use App\Services\V1\ClientService;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LoginWithSocialMediaAction extends Action
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
            'token' => 'required|string',
            'social_auth' => 'required|string|in:facebook,google,apple',
            'fcm_token' => 'required|string',
            'os' => 'required|string|in:android,ios,web',
        ]);

        return DB::transaction(function () use ($validated) {
            try {
                $provider = $validated['social_auth'];
                $provider_user = Socialite::driver($provider)->userFromToken($validated['token']);

                Log::info("Social login attempt", [
                    "provider" => $provider,
                    "provider_user_id" => $provider_user->getId()
                ]);

                // Buscar cuenta social existente
                $socialAccount = SocialAccount::where('provider', $provider)
                    ->where('provider_user_id', $provider_user->getId())
                    ->first();

                $client = null;

                if ($socialAccount) {
                    // Usuario ya existe, obtener el cliente
                    $client = $socialAccount->client;

                    if ($client->status !== 'A') {
                        throw new ValidationErrorException(
                            errors: ["social_auth" => ["Cuenta deshabilitada"]]
                        );
                    }
                } else {
                    // Buscar por email si existe
                    $client = $this->clientService->findBy('email', $provider_user->getEmail());

                    if ($client && $client->status !== 'A') {
                        throw new ValidationErrorException(
                            errors: ["social_auth" => ["Cuenta deshabilitada"]]
                        );
                    }
                }

                if (!$client) {
                    // Crear nuevo cliente
                    $clientData = [
                        'name' => $this->extractName($provider_user, $provider),
                        'email' => $provider_user->getEmail(),
                        'status' => 'A',
                    ];

                    $client = $this->clientService->create($clientData);
                }

                // Crear o actualizar cuenta social
                $socialAccountData = [
                    'client_id' => $client->id,
                    'provider' => $provider,
                    'provider_user_id' => $provider_user->getId(),
                    'email' => $provider_user->getEmail(),
                    'name' => $provider_user->getName(),
                    'avatar' => $provider_user->getAvatar(),
                    'provider_data' => $provider_user->getRaw(),
                ];

                SocialAccount::updateOrCreate(
                    [
                        'provider' => $provider,
                        'provider_user_id' => $provider_user->getId(),
                    ],
                    $socialAccountData
                );

                // Generar token y actualizar FCM
                $token = $this->clientService->saveAuthToken($client, $validated['fcm_token'], $validated['os']);

                return $this->successResult(
                    data: [
                        "token" => $token,
                        "client" => new ClientResource($client)
                    ]
                );

            } catch (\Exception $e) {
                Log::error('Social login error: ' . $e->getMessage(), [
                    'provider' => $validated['social_auth'],
                    'exception' => $e
                ]);

                throw new ValidationErrorException(
                    errors: ["social_auth" => ["Error en el inicio de sesión con " . $validated['social_auth']]]
                );
            }
        });
    }

    /**
     * Extract name from provider user based on provider
     */
    private function extractName($provider_user, string $provider): string
    {
        switch ($provider) {
            case 'google':
                return $provider_user->user['given_name'] ?? $provider_user->getName() ?? '';
            case 'facebook':
                return $provider_user->getName() ?? '';
            case 'apple':
                return $provider_user->getName() ?? '';
            default:
                return $provider_user->getName() ?? '';
        }
    }
}
