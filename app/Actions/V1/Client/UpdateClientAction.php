<?php

declare(strict_types=1);

namespace App\Actions\V1\Client;

use App\Actions\V1\Action;
use App\Support\ActionResult;
use App\Services\V1\ClientService;
use App\Exceptions\ValidationErrorException;
use App\Http\Resources\V1\ClientResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use OpenApi\Annotations as OA;

class UpdateClientAction extends Action
{
    /**
     * @OA\Put(
     *     path="/api/profile",
     *     operationId="updateClientProfile",
     *     tags={"Clientes"},
     *     summary="Actualizar perfil del cliente autenticado",
     *     description="Actualiza la información del perfil del cliente autenticado.",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", format="email", example="cliente@ejemplo.com", description="Email del cliente"),
     *             @OA\Property(property="phone_code", type="string", example="+57", description="Código de país"),
     *             @OA\Property(property="phone", type="string", example="3001234567", description="Teléfono del cliente"),
     *             @OA\Property(property="license_number", type="string", example="ABC123456", description="Número de licencia")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Perfil actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Perfil actualizado exitosamente"),
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
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Datos de validación inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Los datos proporcionados son inválidos"),
     *             @OA\Property(property="errors", type="object")
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
        // Obtener el usuario autenticado
        $user = auth()->user();

        if (!$user) {
            throw new ValidationErrorException([
                'auth' => [trans('auth.failed')]
            ]);
        }

        // Validar solo los campos que se pueden actualizar
        $rules = [
            'email' => ['sometimes', 'required', 'email', Rule::unique('clients')->ignore($user->id)],
            'phone_code' => 'sometimes|required|string|max:5',
            'phone' => 'sometimes|required|string|max:15',
            'license_number' => 'sometimes|required|string|max:255',
        ];

        $validated = $this->validateData($data, $rules);

        // Business logic with transaction
        return DB::transaction(function () use ($validated, $user) {
            // $validated ya solo contiene los campos enviados (gracias a 'sometimes')
            if (empty($validated)) {
                throw new ValidationErrorException([
                    'fields' => [trans('validation.no_fields_sent')]
                ]);
            }

            // Actualizar usuario con los campos validados
            $updatedUser = $this->clientService->update($user->id, $validated);

            if (!$updatedUser) {
                throw new ValidationErrorException([
                    'update' => [trans('validation.error')]
                ]);
            }

            // Return successful result
            return $this->successResult(
                data: [
                    'client' => new ClientResource($updatedUser)
                ]
            );
        });
    }
}
