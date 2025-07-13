<?php

declare(strict_types=1);

namespace App\Services\V1;

use \App\Models\Promotion;
use App\Services\V1\Service;

class PromotionService extends Service
{
    /**
     * Constructor - Set the model class
     */
    public function __construct()
    {
        $this->modelClass = Promotion::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            'title',
        ];

        // Configure pagination
        $this->per_page = 20;
    }

    /**
     * Get active and valid promotions
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActivePromotions(): \Illuminate\Support\Collection
    {
        return $this->modelClass::activeAndValid()
                    ->orderBy('start_date', 'desc')
                    ->get();
    }


}
