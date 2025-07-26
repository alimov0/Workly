<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\UserResource;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Traits\HttpResponses;

class UserController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }
    public function index(Request $request)
    {
        $query = User::query();

        // Qidiruv
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Rol bo‘yicha filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Saralash
        $sortField = $request->input('sort_field', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        // Paginatsiya
        $users = $query->paginate($request->input('per_page', 15));

        return $this->successResponse(UserResource::collection($users), 'Foydalanuvchilar ro‘yxati');
    }
    public function store(UserStoreRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['email_verified_at'] = now();

        $user = User::create($data);

        return $this->successResponse(new UserResource($user), 'Foydalanuvchi yaratildi', 201);
    }

    public function show(User $user)
    {
        return $this->successResponse(new UserResource($user), 'Foydalanuvchi ma\'lumotlari');
    }
    public function update(UserUpdateRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return $this->successResponse(new UserResource($user), 'Foydalanuvchi yangilandi');
    }
    public function destroy(User $user)
    {
        $user->delete();
        return $this->successResponse(null, 'Foydalanuvchi o‘chirildi', 204);
    }
}
