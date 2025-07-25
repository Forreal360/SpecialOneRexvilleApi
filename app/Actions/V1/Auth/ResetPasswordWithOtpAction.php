<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Exceptions\ValidationErrorException;
use App\Services\V1\ClientService;
use App\Models\ClientPasswordOtp;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ResetPasswordWithOtpAction extends Action
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
            'otp_token' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        $otpToken = $validated['otp_token'];
        $newPassword = $validated['new_password'];

        // Validar y decodificar el token OTP
        $otpData = $this->validateOtpToken($otpToken);

        if (!$otpData) {
            throw new ValidationErrorException(
                errors: [
                    "otp_token" => ["Token OTP inválido o expirado."]
                ]
            );
        }

        // Verificar que el OTP original sigue siendo válido
        $otp = ClientPasswordOtp::find($otpData['otp_id']);

        if (!$otp || $otp->isUsed() || $otp->isExpired()) {
            throw new ValidationErrorException(
                errors: [
                    "otp_token" => ["El código OTP ya no es válido."]
                ]
            );
        }

        // Verificar que el email coincide
        if ($otp->email !== $otpData['email']) {
            Log::warning('OTP token email mismatch', [
                'token_email' => $otpData['email'],
                'otp_email' => $otp->email
            ]);

            throw new ValidationErrorException(
                errors: [
                    "otp_token" => ["Token OTP inválido."]
                ]
            );
        }

        // Buscar el cliente
        $client = $this->clientService->findBy('email', $otp->email);

        if (!$client || $client->status !== 'A') {
            throw new ValidationErrorException(
                errors: [
                    "email" => ["Cliente no encontrado o cuenta inactiva."]
                ]
            );
        }

        return DB::transaction(function () use ($client, $otp, $newPassword) {
            try {
                // Actualizar la contraseña del cliente
                $client->password = Hash::make($newPassword);
                $client->save();

                // Marcar el OTP como usado
                $otp->markAsUsed();

                // Revocar todos los tokens de acceso del cliente por seguridad
                $this->clientService->revokeAllTokens($client);

                Log::info('Password reset completed successfully', [
                    'client_id' => $client->id,
                    'email' => $client->email,
                    'otp_id' => $otp->id
                ]);

                return $this->successResult();

            } catch (\Exception $e) {
                Log::error('Failed to reset password', [
                    'client_id' => $client->id,
                    'email' => $client->email,
                    'otp_id' => $otp->id,
                    'error' => $e->getMessage()
                ]);

                throw new ValidationErrorException(
                    errors: [
                        "password" => ["Error al actualizar la contraseña. Intenta nuevamente."]
                    ]
                );
            }
        });
    }

    /**
     * Validar y decodificar el token OTP
     */
    private function validateOtpToken(string $token): ?array
    {
        try {
            $parts = explode('.', $token);

            if (count($parts) !== 2) {
                return null;
            }

            [$hash, $payload] = $parts;

            // Verificar la integridad del token
            $expectedHash = hash('sha256', $payload . config('app.key'));

            if (!hash_equals($expectedHash, $hash)) {
                Log::warning('OTP token integrity check failed');
                return null;
            }

            // Decodificar el payload
            $data = json_decode(base64_decode($payload), true);

            if (!$data || !isset($data['otp_id'], $data['email'], $data['verified_at'], $data['expires_at'])) {
                return null;
            }

            // Verificar que el token no ha expirado (10 minutos después de la verificación)
            if (now()->timestamp > $data['expires_at']) {
                Log::info('OTP token expired', [
                    'expires_at' => $data['expires_at'],
                    'current_time' => now()->timestamp
                ]);
                return null;
            }

            return $data;

        } catch (\Exception $e) {
            Log::warning('Failed to validate OTP token', [
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
}
