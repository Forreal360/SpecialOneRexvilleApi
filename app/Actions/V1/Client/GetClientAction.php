<?php

declare(strict_types=1);

namespace App\Actions\V1\Client;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ClientService;
use App\Exceptions\ValidationErrorException;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\V1\ClientResource;
use OpenApi\Annotations as OA;

class GetClientAction extends Action
{
    /**
     * @OA\Get(
     *     path="/api/profile",
     *     operationId="getClientProfile",
     *     tags={"Clientes"},
     *     summary="Obtener perfil del cliente autenticado",
     *     description="Retorna la información del perfil del cliente autenticado.",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Perfil obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Perfil obtenido exitosamente"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="client", type="object")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="No autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="No autenticado")
     *         )
     *     )
     * )
     */
    /**
     * Constructor - Inject dependencies here
     */
    public function __construct(
        private ClientService $clientService
    ) {
        // UserService injected
    }

    /**
     * Handle the action logic
     *
     * @param array|object $data
     * @return ActionResult
     */
    public function handle($data): ActionResult
    {
        // Business logic with transaction
        return DB::transaction(function () {
            // Obtener el usuario autenticado
            $user = auth()->user();

            if (!$user) {
                throw new ValidationErrorException([
                    'auth' => [trans('auth.failed')]
                ]);
            }

            // Return successful result
            return $this->successResult(
                data: [
                    'client' => new ClientResource($user)
                ],
            );
        });
    }
}
