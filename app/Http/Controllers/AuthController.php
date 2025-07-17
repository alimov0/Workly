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
        $validated = $request->validate();

        $user = User::create([
         'name' => $validated['name'],
         'email' => $validated['email'],
         'password' => Hash::make($validated['password']),
         'role' => $validated['role'],

        ]);
      event(new Registered($user));

      return response()->json([
        'message' =>  'Registration successful. Please check your email for verification.',
        'user' => $user
      ], 201);
    
    
    }
         public function login(LoginRequest $request): JsonResponse
         
         {
            $credentials = $request->validated();

            if(!Auth::attempt($credentials)){
                return response()->json([
              'message' => 'Invalid credentials'
                ], 401);
            
            }
              $user = Auth::user();
              if (!$user->hasVerifiedEmail()) {
                 return response()->json([
                'message' => 'Email not verified. Please verify your email first.'
            ], 403);
              }
              $token = $user->createToken('auth_token')->plainTextToken;

              return response()->json([
                  'access_token' => $token,
                  'token_type' => 'Bearer',
                  'user' => $user
              ]);
          }
          public function logout(): JsonResponse
          {
              Auth::user()->currentAccessToken()->delete();
      
              return response()->json([
                  'message' => 'Successfully logged out'
              ]);
          }
      }
        
        
        
        
        
        
        
        
        


















