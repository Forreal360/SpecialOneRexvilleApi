<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Actions\V1\Auth\LoginWithEmailAction;
use App\Actions\V1\Auth\LogoutAction;
use App\Actions\V1\Auth\RefreshTokenAction;
use App\Actions\V1\Auth\RefreshFcmTokenAction;

use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    public function loginWithEmail(LoginWithEmailAction $action)
    {
        Log::info('LoginController::loginWithEmail', ['request' => request()->all()]);
        $result = $action->execute(request()->all());

        return $result->toApiResponse();
    }

    public function logout(LogoutAction $action)
    {
        Log::info('LoginController::logout', ['request' => request()->all()]);
        $result = $action->execute(request()->all());

        return $result->toApiResponse();
    }

    public function refreshToken(RefreshTokenAction $action)
    {
        Log::info('LoginController::refreshToken', ['request' => request()->all()]);
        $result = $action->execute(request()->all());

        return $result->toApiResponse();
    }

    public function refreshFcmToken(RefreshFcmTokenAction $action)
    {
        Log::info('LoginController::refreshFcmToken', ['request' => request()->all()]);
        $result = $action->execute(request()->all());

        return $result->toApiResponse();
    }
}
