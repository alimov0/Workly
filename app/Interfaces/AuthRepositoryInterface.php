<?php
namespace App\Interfaces\Repositories;

use App\Models\User;
use App\DTO\Auth\RegisterDTO;

interface AuthRepositoryInterface
{
    public function create(RegisterDTO $dto): User;
}
