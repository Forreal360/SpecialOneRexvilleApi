<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api\Auth;

use App\Http\Controllers\Controller;
use App\Actions\V1\Auth\SendPasswordResetOtpAction;
use App\Actions\V1\Auth\VerifyPasswordResetOtpAction;
use App\Actions\V1\Auth\ResetPasswordWithOtpAction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PasswordResetController extends Controller
{
    /**
     * Enviar código OTP para recuperación de contraseña
     */
    public function sendOtp(Request $request, SendPasswordResetOtpAction $action)
    {
        Log::info('PasswordResetController::sendOtp', ['request' => $request->only(['email'])]);

        $result = $action->execute($request->all());

        return $result->toApiResponse();
    }

    /**
     * Verificar código OTP
     */
    public function verifyOtp(Request $request, VerifyPasswordResetOtpAction $action)
    {
        Log::info('PasswordResetController::verifyOtp', ['request' => $request->only(['email'])]);

        $result = $action->execute($request->all());

        return $result->toApiResponse();
    }

    /**
     * Resetear contraseña con token OTP verificado
     */
    public function resetPassword(Request $request, ResetPasswordWithOtpAction $action)
    {
        Log::info('PasswordResetController::resetPassword', ['request' => $request->only(['email'])]);

        $result = $action->execute($request->all());

        return $result->toApiResponse();
    }
}
