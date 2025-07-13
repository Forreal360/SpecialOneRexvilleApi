<?php

namespace App\Http\Controllers\V1\Api\Auth;

use App\Http\Controllers\Controller;
use App\Actions\V1\Auth\LoginWithSocialMediaAction;
use App\Actions\V1\Auth\ConnectSocialAccountAction;
use App\Actions\V1\Auth\DisconnectSocialAccountAction;
use App\Actions\V1\Auth\GetSocialAccountsAction;
use Illuminate\Http\Request;

class SocialAuthController extends Controller
{
    /**
     * Login with social media
     */
    public function loginWithSocial(Request $request, LoginWithSocialMediaAction $action)
    {
        $result = $action->handle($request->all());
        return response()->json($result->toArray(), $result->getStatusCode());
    }

    /**
     * Connect social account to profile
     */
    public function connectSocialAccount(Request $request, ConnectSocialAccountAction $action)
    {
        $result = $action->handle($request->all());
        return response()->json($result->toArray(), $result->getStatusCode());
    }

    /**
     * Disconnect social account from profile
     */
    public function disconnectSocialAccount(Request $request, DisconnectSocialAccountAction $action)
    {
        $result = $action->handle($request->all());
        return response()->json($result->toArray(), $result->getStatusCode());
    }

    /**
     * Get connected social accounts
     */
    public function getSocialAccounts(Request $request, GetSocialAccountsAction $action)
    {
        $result = $action->handle($request->all());
        return response()->json($result->toArray(), $result->getStatusCode());
    }
}
