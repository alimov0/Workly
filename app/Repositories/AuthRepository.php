<?php
namespace App\Repositories;

use App\Models\User;
use App\DTO\Auth\RegisterDTO;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\Repositories\AuthRepositoryInterface;


class AuthRepository implements AuthRepositoryInterface
{
    public function create(RegisterDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'role' => $dto->role,
        ]);
    }
}
