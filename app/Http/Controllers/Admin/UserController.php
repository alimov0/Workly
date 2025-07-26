<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\Admin\UserResource;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use Throwable;

class UserController extends Controller
{

    public function index(Request $request)
    {
        try {
            $query = User::query();

            // Qidiruv
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%$search%")
                      ->orWhere('email', 'like', "%$search%");
                });
            }

            // Role bo‘yicha filter
            if ($request->filled('role')) {
                $query->where('role', $request->role);
            }

            // Saralash
            $sortField = $request->input('sort_field', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            // Paginatsiya
            $users = $query->paginate($request->input('per_page', 15));

            return response()->json([
                'status' => 'success',
                'message' => 'Foydalanuvchilar ro‘yxati',
                'data'    => UserResource::collection($users)
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Foydalanuvchilarni yuklashda xatolik',
            ], 500);
        }
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $data = $request->validated();
            $data['password'] = Hash::make($data['password']);
            $data['email_verified_at'] = now();

            $user = User::create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Foydalanuvchi yaratildi',
                'data'    => new UserResource($user)
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Foydalanuvchini yaratishda xatolik',
            ], 500);
        }
    }

    public function show(User $user)
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => 'Foydalanuvchi maʼlumotlari',
                'data'    => new UserResource($user)
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Foydalanuvchini yuklashda xatolik',
            ], 500);
        }
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $data = $request->validated();

            if ($request->filled('password')) {
                $data['password'] = Hash::make($data['password']);
            }

            $user->update($data);

            return response()->json([
                'status' => 'success',
                'message' => 'Foydalanuvchi yangilandi',
                'data'    => new UserResource($user)
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Foydalanuvchini yangilashda xatolik',
            ], 500);
        }
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Foydalanuvchi o‘chirildi',
                'data'    => null
            ], 204);
        } catch (Throwable $e) {
            return response()->json([
                'status' => 'error', 
                'message' => 'Foydalanuvchini o‘chirishda xatolik',
            ], 500);
        }
    }
}
