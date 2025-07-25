<?php

declare(strict_types=1);

namespace App\Http\Controllers\V1\Api;

use App\Http\Controllers\Controller;
use App\Utilities\TimezoneHelper;
use Illuminate\Http\JsonResponse;

class TimezoneController extends Controller
{
    /**
     * Get available timezones
     */
    public function index(): JsonResponse
    {
        $timezones = TimezoneHelper::getCommonTimezones();

        return response()->json([
            'success' => true,
            'message' => 'Timezones obtenidos exitosamente.',
            'data' => $timezones
        ]);
    }
}
