<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Api\BaseController;
use App\Http\Resources\Admin\UserResource;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;

class UserController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    // Barcha foydalanuvchilar
    public function index(Request $request)
    {
        $query = User::query();

        // Qidiruv va filtrlar
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        // Saralash
        $sortField = $request->sort_field ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        $users = $query->paginate($request->per_page ?? 15);

        return $this->successResponse(UserResource::collection($users), 'Foydalanuvchilar ro\'yxati');
    }

    // Yangi foydalanuvchi
    public function store(UserStoreRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return $this->successResponse(new UserResource($user), 'Foydalanuvchi yaratildi', 201);
    }

    // Foydalanuvchi ma'lumotlari
    public function show(User $user)
    {
        return $this->successResponse(new UserResource($user), 'Foydalanuvchi ma\'lumotlari');
    }

    // Foydalanuvchini yangilash
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return $this->successResponse(new UserResource($user), 'Foydalanuvchi yangilandi');
    }

    // Foydalanuvchini o'chirish
    public function destroy(User $user)
    {
        $user->delete();
        return $this->successResponse(null, 'Foydalanuvchi o\'chirildi', 204);
    }
}
