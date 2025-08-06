<?php
namespace App\Interfaces\Services;

use App\DTO\Auth\LoginDTO;
use App\DTO\Auth\RegisterDTO;

interface AuthServiceInterface
{
    public function register(RegisterDTO $dto): array;
    public function login(LoginDTO $dto): array|null;
    public function logout(): void;
}
