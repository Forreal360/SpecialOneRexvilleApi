<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Exceptions\ValidationErrorException;
use App\Services\V1\ClientService;
use App\Models\ClientPasswordOtp;
use App\Models\Client;
use App\Mail\PasswordResetOtpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendPasswordResetOtpAction extends Action
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
            'email' => 'required|email|exists:clients,email',
        ]);

        $email = $validated['email'];

        // Verificar que el cliente existe y está activo
        $client = $this->clientService->findBy('email', $email);

        if($client === null || $client->status !== 'A') {
            throw new ValidationErrorException(
                errors: [
                    "email" => ["El email no está registrado o la cuenta no está activa."]
                ]
            );
        }

        // Verificar límite de intentos (máximo 3 códigos por hora)
        $recentOtps = ClientPasswordOtp::where('email', $email)
            ->where('created_at', '>=', now()->subHour())
            ->count();

        if ($recentOtps >= 3) {
            throw new ValidationErrorException(
                errors: [
                    "email" => ["Has alcanzado el límite de códigos por hora. Intenta más tarde."]
                ]
            );
        }

        // Crear nuevo código OTP
        $otp = ClientPasswordOtp::createForEmail($email, 15); // 15 minutos de expiración

        try {
            // Enviar email con el código OTP
            $this->sendOtpEmail($client, $otp->otp_code);

            Log::info('Password reset OTP sent', [
                'email' => $email,
                'otp_id' => $otp->id
            ]);

            return $this->successResult(
                data: [
                    'expires_at' => $otp->expires_at->format('Y-m-d H:i:s')
                ]
            );

        } catch (\Exception $e) {
            // Si falla el envío, marcar el OTP como usado para evitar intentos
            $otp->markAsUsed();

            Log::error('Failed to send password reset OTP', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            throw new ValidationErrorException(
                errors: [
                    "email" => ["Error al enviar el código. Intenta nuevamente."]
                ]
            );
        }
    }

        /**
     * Enviar email con código OTP
     */
    private function sendOtpEmail(Client $client, string $otpCode): void
    {
        // Enviar email real con código OTP
        Mail::to($client->email)->send(new PasswordResetOtpMail(
            clientName: $client->name,
            otpCode: $otpCode,
            expiresIn: '15 minutos'
        ));

        Log::info('Password reset OTP email sent', [
            'to' => $client->email,
            'client_name' => $client->name
        ]);
    }
}
