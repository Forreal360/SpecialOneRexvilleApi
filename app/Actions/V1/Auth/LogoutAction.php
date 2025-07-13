<?php

declare(strict_types=1);

namespace App\Actions\V1\Auth;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ClientService;
use Illuminate\Http\Request;

class LogoutAction extends Action
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

        $user = auth()->user();

        $this->clientService->revokeAuthToken($user);

        return $this->successResult();

    }
}
