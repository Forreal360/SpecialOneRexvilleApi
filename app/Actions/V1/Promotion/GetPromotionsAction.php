<?php

declare(strict_types=1);

namespace App\Actions\V1\Promotion;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\PromotionService;
use App\Http\Resources\V1\PromotionResource;

class GetPromotionsAction extends Action
{
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(
        private PromotionService $promotionService
    ) {}

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {
        // Obtener todas las promociones activas y válidas
        $promotions = $this->promotionService->getActivePromotions();

        return $this->successResult(
            data: [
                'promotions' => PromotionResource::collection($promotions)
            ]
        );
    }
}
