<?php

declare(strict_types=1);

namespace App\Services\V1;

use \App\Models\Client;
use App\Services\V1\Service;

class ClientService extends Service
{
    /**
     * Constructor - Set the model class
     */
    public function __construct()
    {
        $this->modelClass = Client::class;

        // Configure searchable fields for this service
        $this->searchableFields = [
            'name',
            'email',
        ];

        // Configure pagination
        $this->per_page = 10;
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findBy('email', $email);
    }

    /**
     * Get active users
     *
     * @return \Illuminate\Support\Collection
     */
    public function getActiveUsers()
    {
        return $this->modelClass::where('status', 'A')->get();
    }

    /**
     * Update user profile
     *
     * @param int $userId
     * @param array $data
     * @return User|null
     */
    public function updateProfile(int $userId, array $data): ?User
    {
        return $this->update($userId, $data);
    }

    /**
     * Save auth token
     *
     * @param Client $client
     * @param string $fcmToken
     * @param string $os
     * @return string
     */
    public function saveAuthToken(Client $client, string $fcmToken, string $os): string
    {
        $token = $client->createToken('auth_token')->plainTextToken;

        $id_token = explode('|', $token);

        $register_token = $client->tokens()->where('id', $id_token[0])->first();

        $register_token->forceFill([
            'fcm_token' => $fcmToken,
            'os' => $os,
        ]);

        $register_token->save();

        return $token;
    }

    /**
     * Revoke auth token
     *
     * @param Client $client
     * @param string $fcmToken
     * @return void
     */
    public function revokeAuthToken(Client $client): void
    {
        $client->currentAccessToken()->delete();
    }

    /**
     * Refresh auth token
     *
     * @param Client $client
     * @param string $fcmToken
     * @param string $os
     * @return string
     */
    public function refreshAuthToken(Client $client, string $fcmToken, string $os): string
    {
        // Revoke current token
        $client->currentAccessToken()->delete();

        // Create new token
        return $this->saveAuthToken($client, $fcmToken, $os);
    }

    /**
     * Refresh FCM token
     *
     * @param Client $client
     * @param string $fcmToken
     * @param string $os
     * @return void
     */
    public function refreshFcmToken(Client $client, string $fcmToken, string $os): void
    {
        $currentToken = $client->currentAccessToken();

        if ($currentToken) {
            $currentToken->forceFill([
                'fcm_token' => $fcmToken,
                'os' => $os,
            ]);
            $currentToken->save();
        }
    }
}
