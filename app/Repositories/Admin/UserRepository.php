<?php

namespace App\Repositories\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\DTO\Admin\UserCreateDTO;
use App\DTO\Admin\UserUpdateDTO;
use App\Interfaces\Repositories\Admin\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function getAll(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        return $query->orderBy($sortField, $sortOrder)
                     ->paginate($request->input('per_page', 15));
    }

    public function create(UserCreateDTO $dto): User
    {
        return User::create([
            'name' => $dto->name,
            'email' => $dto->email,
            'password' => Hash::make($dto->password),
            'email_verified_at' => now(),
            'role' => $dto->role,
        ]);
    }

    public function update(User $user, UserUpdateDTO $dto): User
    {
        $data = [];

        if ($dto->name) $data['name'] = $dto->name;
        if ($dto->email) $data['email'] = $dto->email;
        if ($dto->role) $data['role'] = $dto->role;

        if ($dto->password) {
            $data['password'] = Hash::make($dto->password);
        }

        $user->update($data);
        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
