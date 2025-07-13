<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Exceptions\ValidationErrorException;
use App\Models\SocialAccount;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class DisconnectSocialAccountAction extends Action
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
            'social_auth' => 'required|string|in:facebook,google,apple',
        ]);

        return DB::transaction(function () use ($validated) {
            try {
                $client = Auth::user();
                $provider = $validated['social_auth'];

                Log::info("Social account disconnection attempt", [
                    "client_id" => $client->id,
                    "provider" => $provider
                ]);

                // Buscar la cuenta social del cliente
                $socialAccount = SocialAccount::where('client_id', $client->id)
                    ->where('provider', $provider)
                    ->first();

                if (!$socialAccount) {
                    throw new ValidationErrorException(
                        errors: ["social_auth" => ["No tienes una cuenta de " . $provider . " conectada"]]
                    );
                }

                // Verificar que el cliente tenga al menos un método de autenticación
                $totalSocialAccounts = SocialAccount::where('client_id', $client->id)->count();

                // Si solo tiene esta cuenta social y no tiene contraseña, no permitir desconectar
                if ($totalSocialAccounts === 1 && !$client->password) {
                    throw new ValidationErrorException(
                        errors: ["social_auth" => ["No puedes desconectar tu única cuenta de autenticación"]]
                    );
                }

                // Eliminar la cuenta social
                $socialAccount->delete();

                return $this->successResult(
                    data: [
                        "message" => "Cuenta de " . $provider . " desconectada exitosamente"
                    ]
                );

            } catch (\Exception $e) {
                Log::error('Social account disconnection error: ' . $e->getMessage(), [
                    'client_id' => Auth::id(),
                    'provider' => $validated['social_auth'],
                    'exception' => $e
                ]);

                if ($e instanceof ValidationErrorException) {
                    throw $e;
                }

                throw new ValidationErrorException(
                    errors: ["social_auth" => ["Error al desconectar cuenta de " . $validated['social_auth']]]
                );
            }
        });
    }
}
