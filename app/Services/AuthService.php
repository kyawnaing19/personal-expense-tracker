<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepositories;
use Google\Client as GoogleClient;

class AuthService
{
    public function __construct(
        private UserRepositories $userRepository
    ) {}

    public function googleLogin(string $idToken): array
    {
        $googleUser = $this->verifyGoogleToken($idToken);
        $user = $this->userRepository->findOrCreateByGoogle($googleUser);
        $token = $user->createToken('mobile-app')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token,
        ];
    }

    private function verifyGoogleToken(string $idToken): array
    {
        $client = new GoogleClient();
        $client->setClientId(config('services.google.client_id'));

        $payload = $client->verifyIdToken($idToken);

        if (!$payload) {
            throw new \Exception('Invalid Google token', 401);
        }

        return [
            'google_id' => $payload['sub'],
            'email'     => $payload['email'],
            'name'      => $payload['name'],
            'avatar'    => $payload['picture'] ?? null,
        ];
    }

    public function logout(User $user): void
    {
        $user->currentAccessToken()->delete();
    }

    public function updateFcmToken(User $user, string $token): void
    {
        $this->userRepository->updateFcmToken($user, $token);
    }
}
