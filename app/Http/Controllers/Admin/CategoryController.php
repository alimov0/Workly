<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\DTO\Admin\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use App\Interfaces\Services\Admin\CategoryServiceInterface;

class CategoryController extends Controller
{
    protected CategoryServiceInterface $categoryService;

    public function __construct(CategoryServiceInterface $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Barcha kategoriyalarni olish
     */
    public function index(Request $request)
    {
        try {
            $categories = $this->categoryService->getAll($request);

            return response()->json([
                'status' => 'success',
                'message' => __('Categories retrieved successfully'),
                'data' => CategoryResource::collection($categories)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'success',
                'message' => __('Failed to load categories'),
                'data' => $categories
            ]);
        }
    }

    /**
     * Yangi kategoriya yaratish
     */
    public function store(CategoryStoreRequest $request)
    {
        try {
            $dto = CategoryDTO::fromRequest($request);
            $category = $this->categoryService->store($dto);

            return response()->json([
                'status' => 'success',
                'message' => __('Category created successfully'),
                'data' => new CategoryResource($category)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'success',
                'message' => __('Failed to create category'),
                'data' => $category
            ]);
        }
    }

    /**
     * Bitta kategoriyani ko‘rish
     */
    public function show($id)
    {
        try {
            $category = $this->categoryService->show($id);

            return response()->json([
                'status' => 'success',
                'message' => __('Category details retrieved'),
                'data' => new CategoryResource($category)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'success',
                'message' => __('Failed to load category'),
                'data' => $category
            ]);
        }
    }

    /**
     * Kategoriya yangilash
     */
    public function update(CategoryUpdateRequest $request, $id)
    {
        try {
            $dto = CategoryDTO::fromRequest($request);
            $category = $this->categoryService->update($dto, $id);

            return response()->json([
                'status' => 'success',
                'message' => __('Category updated successfully'),
                'data' => new CategoryResource($category)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'success',
                'message' => __('Failed to update category'),
                'data' => $category
            ]);
        }
    }

    /**
     * Kategoriya o‘chirish
     */
    public function destroy($id)
    {
        try {
            $this->categoryService->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => __('Category deleted successfully')
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'success',
                'message' => __('Failed to delete category'),
                'data' => $e->getMessage()
            ]);
        }
    }
}
