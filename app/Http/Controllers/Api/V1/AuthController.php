<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GoogleLoginRequest;
use App\Http\Requests\Auth\FcmTokenRequest;
use App\Http\Resources\UserResource;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(
        private AuthService $authService
    ) {}

    public function googleLogin(GoogleLoginRequest $request): JsonResponse
    {
        try {
            $result = $this->authService->googleLogin(
                $request->validated('id_token')
            );

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data'    => [
                    'user'  => new UserResource($result['user']),
                    'token' => $result['token'],
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], (int)$e->getCode() ?: 500);
        }
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data'    => new UserResource($request->user()),
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }

    public function updateFcmToken(FcmTokenRequest $request): JsonResponse
    {
        $this->authService->updateFcmToken(
            $request->user(),
            $request->validated('fcm_token')
        );

        return response()->json([
            'success' => true,
            'message' => 'FCM token updated',
        ]);
    }
}
