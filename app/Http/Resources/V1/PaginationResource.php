<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

class PaginationResource extends JsonResource
{

    public $key;
    public $pagination;

    public function __construct(array $resource, LengthAwarePaginator $pagination, string $key = "registers")
    {
        parent::__construct($resource);
        $this->pagination = $pagination->toArray();
        $this->key = $key;
    }

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            $this->key => $this->resource,
            "pagination" => [
                "current_page" => $this->pagination["current_page"],
                "total" => $this->pagination["total"],
                "per_page" => $this->pagination["per_page"],
                "last_page" => $this->pagination["last_page"],
                "first_page_url" => $this->pagination["first_page_url"],
                "last_page_url" => $this->pagination["last_page_url"],
                "next_page_url" => $this->pagination["next_page_url"],
                "prev_page_url" => $this->pagination["prev_page_url"],
                "from" => $this->pagination["from"],
                "to" => $this->pagination["to"],
                "path" => $this->pagination["path"],
            ]
        ];
    }
}
