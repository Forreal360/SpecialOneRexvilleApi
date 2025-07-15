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
            
            $provider = $validated['social_auth'];
            $provider_user = Socialite::driver($provider)->userFromToken($validated['token']);
            
            $email = $provider_user->email;

            // Buscar cuenta social existente
            $socialAccount = SocialAccount::where('provider', $provider)
                ->where('provider_user_id', $provider_user->getId())
                ->first();
            
            if($socialAccount == null){
                throw new ValidationErrorException(
                    errors: ["social_auth" => ["El cliente no existe. No se puede iniciar sesión."]]
                );
            }

            $client = null;

            $client = $socialAccount->client;
            

            if (!$client) {
                // Si el cliente no existe, lanzar excepción
                throw new ValidationErrorException(
                    errors: ["social_auth" => ["El cliente no existe. No se puede iniciar sesión."]]
                );
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

            
        });
    }

}
