<?php
namespace App\Services;

use App\Events\UserRegistered;
use Illuminate\Support\Facades\Auth;
use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;
use App\Interfaces\Services\AuthServiceInterface;
use App\Interfaces\Repositories\AuthRepositoryInterface;

class AuthService implements AuthServiceInterface
{
    public function __construct(
        protected AuthRepositoryInterface $userRepository
    ) {}

    public function register(RegisterDTO $dto): array
    {
        $user = $this->userRepository->create($dto);

        event(new UserRegistered($user));

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function login(LoginDTO $dto): array|null
    {
        if (!Auth::attempt($dto->toCredentials())) {
            return null;
        }

        $user = Auth::user();

        if (!$user->hasVerifiedEmail()) {
            throw new \Exception('Email not verified', 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    public function logout(): void
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens->each(fn($token) => $token->delete());
        }
    }
}
