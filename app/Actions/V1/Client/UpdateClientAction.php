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
            'name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('clients')->ignore($user->id)],
            'phone_code' => 'sometimes|required|string|max:5',
            'phone' => 'sometimes|required|string|max:15',
            'license_number' => 'sometimes|required|string|max:255',
            'profile_photo' => 'sometimes|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',  
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
