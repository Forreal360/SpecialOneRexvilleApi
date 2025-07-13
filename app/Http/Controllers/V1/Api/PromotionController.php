<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Actions\V1\Promotion\GetPromotionsAction;
use Illuminate\Http\JsonResponse;


class PromotionController extends Controller
{

    public function index(GetPromotionsAction $action): JsonResponse
    {
        $result = $action->execute([]);

        return $result->toApiResponse();
    }
}
