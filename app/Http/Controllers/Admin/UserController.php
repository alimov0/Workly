<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\DTO\Admin\UserCreateDTO;
use App\DTO\Admin\UserUpdateDTO;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\UserResource;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Interfaces\Services\Admin\UserServiceInterface;

class UserController extends Controller
{
    protected UserServiceInterface $service;

    public function __construct(UserServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(): JsonResponse
    {
        try {
            $users = $this->service->getAll();
            return response()->json([
                'status' => 'success',
                'message' => __('Users retrieved successfully'),
                'data' => UserResource::collection($users)
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Failed to load users'));
        }
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        try {
            $dto = UserCreateDTO::fromRequest($request);
            $user = $this->service->create($dto);

            return response()->json([
                'status' => 'success',
                'message' => __('User created successfully'),
                'data' => new UserResource($user)
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Failed to create user'));
        }
    }

    public function show(string $id): JsonResponse
    {
        try {
            $user = $this->service->getById($id);
            return response()->json([
                'status' => 'success',
                'message' => __('User detail loaded'),
                'data' => new UserResource($user)
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Failed to load user'));
        }
    }

    public function update(UserUpdateRequest $request, string $id): JsonResponse
    {
        try {
            $dto = UserUpdateDTO::fromRequest($request);
            $user = $this->service->update($dto, $id);

            return response()->json([
                'status' => 'success',
                'message' => __('User updated successfully'),
                'data' => new UserResource($user)
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Failed to update user'));
        }
    }

    public function destroy(string $id): JsonResponse
    {
        try {
            $this->service->delete($id);
            return response()->json([
                'status' => 'success',
                'message' => __('User deleted successfully')
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Failed to delete user'));
        }
    }

    /**
     * Xatoliklarni JSON ko'rinishida qaytarish
     */
    protected function errorResponse(Throwable $e, string $message = 'Error'): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $e->getMessage()
        ]);
    }
}
