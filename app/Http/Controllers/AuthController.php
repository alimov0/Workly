<?php

namespace App\Http\Controllers;

use Throwable;
use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Interfaces\Services\AuthServiceInterface;

class AuthController extends Controller
{
    protected AuthServiceInterface $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $dto = RegisterDTO::fromRequest($request);
            $data = $this->authService->register($dto);

            return response()->json([
                'message' => 'Ro‘yxatdan o‘tish muvaffaqiyatli. Emailni tasdiqlang.',
                'user' => $data['user']->makeHidden(['password', 'remember_token']),
                'token' => $data['token'],
            ], 201);
        } catch (Throwable $e) {
            return $this->errorResponse($e, 'Ro‘yxatdan o‘tishda xatolik yuz berdi.');
        }
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginDTO::fromRequest($request);
            $data = $this->authService->login($dto);

            if (!$data) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Login yoki parol noto‘g‘ri.',
                ], 401);
            }

            return response()->json([
                'message' => 'Tizimga muvaffaqiyatli kirildi.',
                'access_token' => $data['token'],
                'token_type' => 'Bearer',
                'user' => $data['user']->makeHidden(['password', 'remember_token']),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, 'Kirishda xatolik yuz berdi.');
        }
    }

    public function logout(): JsonResponse
    {
        try {
            $this->authService->logout();

            return response()->json([
                'message' => 'Tizimdan chiqildi.',
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, 'Tizimdan chiqishda xatolik yuz berdi.');
        }
    }

    protected function errorResponse(Throwable $e, string $message, int $status = 500): JsonResponse
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null,
        ], $status);
    }
}
