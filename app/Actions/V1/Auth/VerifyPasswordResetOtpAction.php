<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Exceptions\ValidationErrorException;
use App\Models\ClientPasswordOtp;
use Illuminate\Support\Facades\Log;

class VerifyPasswordResetOtpAction extends Action
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
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        $email = $validated['email'];
        $otpCode = $validated['otp_code'];

        // Buscar el código OTP válido más reciente para este email
        $otp = ClientPasswordOtp::byEmail($email)
            ->where('otp_code', $otpCode)
            ->where('is_used', false)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$otp) {
            Log::warning('Invalid OTP code attempted', [
                'email' => $email,
                'otp_code' => $otpCode
            ]);

            throw new ValidationErrorException(
                errors: [
                    "otp_code" => ["Código OTP inválido."]
                ]
            );
        }

        // Incrementar intentos
        $otp->incrementAttempts();

        // Verificar límite de intentos (máximo 5 intentos por código)
        if ($otp->attempts > 5) {
            $otp->markAsUsed();

            Log::warning('OTP code blocked due to too many attempts', [
                'email' => $email,
                'otp_id' => $otp->id,
                'attempts' => $otp->attempts
            ]);

            throw new ValidationErrorException(
                errors: [
                    "otp_code" => ["Código bloqueado por múltiples intentos fallidos. Solicita uno nuevo."]
                ]
            );
        }

        // Verificar si el código está expirado
        if ($otp->isExpired()) {
            $otp->markAsUsed();

            Log::info('Expired OTP code attempted', [
                'email' => $email,
                'otp_id' => $otp->id,
                'expired_at' => $otp->expires_at->format('Y-m-d H:i:s')
            ]);

            throw new ValidationErrorException(
                errors: [
                    "otp_code" => ["El código OTP ha expirado. Solicita uno nuevo."]
                ]
            );
        }

        // Verificar si el código ya fue usado
        if ($otp->isUsed()) {
            Log::warning('Already used OTP code attempted', [
                'email' => $email,
                'otp_id' => $otp->id
            ]);

            throw new ValidationErrorException(
                errors: [
                    "otp_code" => ["Este código ya fue utilizado."]
                ]
            );
        }

        // El código es válido
        Log::info('OTP code verified successfully', [
            'email' => $email,
            'otp_id' => $otp->id
        ]);

        return $this->successResult(
            data: [
                'otp_token' => $this->generateOtpToken($otp), // Token temporal para el siguiente paso
                'expires_at' => $otp->expires_at->format('Y-m-d H:i:s')
            ]
        );
    }

    /**
     * Generar token temporal para validar el reseteo de contraseña
     */
    private function generateOtpToken(ClientPasswordOtp $otp): string
    {
        // Crear un token temporal que incluya el ID del OTP y un timestamp
        $payload = base64_encode(json_encode([
            'otp_id' => $otp->id,
            'email' => $otp->email,
            'verified_at' => now()->timestamp,
            'expires_at' => now()->addMinutes(10)->timestamp // 10 minutos para completar el reset
        ]));

        return hash('sha256', $payload . config('app.key')) . '.' . $payload;
    }
}
