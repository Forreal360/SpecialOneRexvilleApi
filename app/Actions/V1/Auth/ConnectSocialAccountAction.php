<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Exceptions\ValidationErrorException;
use App\Models\SocialAccount;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ConnectSocialAccountAction extends Action
{
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
        ]);

        return DB::transaction(function () use ($validated) {
            
            $client = Auth::user();
            $provider = $validated['social_auth'];
            $provider_user = Socialite::driver($provider)->userFromToken($validated['token']);

            Log::info("Social account connection attempt", [
                "client_id" => $client->id,
                "provider" => $provider,
                "provider_user_id" => $provider_user->getId()
            ]);

            

            // Verificar si ya existe una cuenta social con este provider_user_id
            $existingAccount = SocialAccount::where('provider', $provider)
                ->where('provider_user_id', $provider_user->getId())
                ->first();

            if ($existingAccount) {
                if ($existingAccount->client_id !== $client->id) {
                    throw new ValidationErrorException(
                        errors: ["social_auth" => ["Esta cuenta de " . $provider . " ya está conectada a otro usuario"]]
                    );
                } else {
                    throw new ValidationErrorException(
                        errors: ["social_auth" => ["Esta cuenta de " . $provider . " ya está conectada a tu perfil"]]
                    );
                }
            }

            // Verificar si el cliente ya tiene una cuenta de este proveedor
            $clientSocialAccount = SocialAccount::where('client_id', $client->id)
                ->where('provider', $provider)
                ->first();

            if ($clientSocialAccount) {
                throw new ValidationErrorException(
                    errors: ["social_auth" => ["Ya tienes una cuenta de " . $provider . " conectada"]]
                );
            }

            // Crear nueva cuenta social
            $socialAccountData = [
                'client_id' => $client->id,
                'provider' => $provider,
                'provider_user_id' => $provider_user->getId(),
                'email' => $provider_user->getEmail(),
                'name' => $provider_user->getName(),
                'avatar' => $provider_user->getAvatar(),
                'provider_data' => $provider_user->getRaw(),
            ];

            $socialAccount = SocialAccount::create($socialAccountData);

            return $this->successResult();

            
        });
    }
}
