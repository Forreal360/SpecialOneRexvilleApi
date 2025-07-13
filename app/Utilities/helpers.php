<?php
use Illuminate\Support\Str;
use App\Http\Resources\V1\PaginationResource;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Resources\V1\NotificationResource;

/**
 * Generates a random code of uppercase letters and numbers
 * @param int $length
 * @return string
 */
if (! function_exists('getRandomCode')) {
    function getRandomCode(int $length = 5): string {
        $code = strtoupper(Str::random($length));
        return $code;
    }
}

/**
 * Generate pagination resource
 * @param array $resource
 * @param LengthAwarePaginator $pagination
 * @param string $class
 * @param string $key
 * @return PaginationResource
 */
if (! function_exists('generatePaginationResource')) {
    function generatePaginationResource(LengthAwarePaginator $pagination, string $class, string $key = "registers") {

        $registers = $class::collection($pagination->toArray()["data"])->resolve();

        $pagination = PaginationResource::make($registers, $pagination, $key);

        return $pagination;
    }
}
