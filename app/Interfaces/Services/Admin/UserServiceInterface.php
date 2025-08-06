<?php

namespace App\Interfaces\Services\Admin;

use App\DTO\Admin\UserCreateDTO;
use App\DTO\Admin\UserUpdateDTO;
use Illuminate\Http\Request;
use App\Models\User;

interface UserServiceInterface
{
    public function getAll(Request $request);
    public function create(UserCreateDTO $dto): User;
    public function update(User $user, UserUpdateDTO $dto): User;
    public function delete(User $user): void;
}
