<?php

namespace App\Interfaces\Repositories\Admin;

use Illuminate\Http\Request;
use App\Models\User;
use App\DTO\Admin\UserCreateDTO;
use App\DTO\Admin\UserUpdateDTO;

interface UserRepositoryInterface
{
    public function getAll(Request $request);
    public function create(UserCreateDTO $dto): User;
    public function update(User $user, UserUpdateDTO $dto): User;
    public function delete(User $user): void;
}
