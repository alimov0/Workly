<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

    $user = User::create([
        'name' => $validated['name'],
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'role' => $validated['role'],
    ]);

    // Email tasdiqlash uchun event yuboriladi
    event(new Registered($user));

    // Sanctum token yaratish
    $token = $user->createToken('auth_token')->plainTextToken;

    // Javobni yig'ish
    $response = [
        'message' => 'Registration successful. Please check your email for verification.',
        'user'    => $user->makeHidden(['password', 'remember_token']),
        'token'   => $token,
    ];

    return response()->json($response, 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        // Auth::attempt orqali foydalanuvchini tekshiramiz
        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
    
        // Auth'dan keyin foydalanuvchini olish
        $user = Auth::user();
    
        // Email tasdiqlanganmi?
        if (!$user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Email not verified. Please verify your email first.'
            ], 403);
        }
    
        // Token yaratish
        $token = $user->createToken('auth_token')->plainTextToken;
    
        // Javobni yuborish
        return response()->json([
            'message' => 'Successfully Logged In',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user->makeHidden(['password', 'remember_token']),
        ], 200);
    }

    public function logout(): JsonResponse
    {
        $user = Auth::user();

    // Barcha tokenlarni o'chirish (agar foydalanuvchi tizimga kirgan bo‘lsa)
    if ($user) {
        $user->tokens->each(function ($token) {
            $token->delete(); // yoki forceDelete() agar tokenlar soft delete bo‘lsa
        });
    }

    return response()->json([
        'message' => 'Logged out successfully',
    ], 200);
    }


}