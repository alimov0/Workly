<?php

namespace App\Services\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\DTO\Admin\UserCreateDTO;
use App\DTO\Admin\UserUpdateDTO;
use App\Interfaces\Services\Admin\UserServiceInterface;
use App\Interfaces\Repositories\Admin\UserRepositoryInterface;

class UserService implements UserServiceInterface
{
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAll(Request $request)
    {
        return $this->userRepository->getAll($request);
    }

    public function create(UserCreateDTO $dto): User
    {
        return $this->userRepository->create($dto);
    }

    public function update(User $user, UserUpdateDTO $dto): User
    {
        return $this->userRepository->update($user, $dto);
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}
