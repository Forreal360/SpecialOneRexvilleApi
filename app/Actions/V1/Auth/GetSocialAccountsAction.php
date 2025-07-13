<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Http\Resources\V1\SocialAccountResource;
use Illuminate\Support\Facades\Auth;

class GetSocialAccountsAction extends Action
{
    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {
        $client = Auth::user();

        $socialAccounts = $client->socialAccounts()
            ->select(['id', 'provider', 'email', 'name', 'avatar', 'created_at'])
            ->get();

        return $this->successResult(
            data: [
                "social_accounts" => SocialAccountResource::collection($socialAccounts)
            ]
        );
    }
}
