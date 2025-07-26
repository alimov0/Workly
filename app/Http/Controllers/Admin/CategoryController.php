<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{

    public function index(Request $request)
    {
        try {
            $query = Category::with(['parent', 'children']);

            if ($request->filled('search')) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }

            if ($request->filled('parent_id')) {
                $query->where('parent_id', $request->parent_id);
            }

            $categories = $query->paginate($request->per_page ?? 15);

            return response()->json([
                'success' => true,
                'message' => __('Categories retrieved successfully'),
                'data'    => CategoryResource::collection($categories),
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Category Index Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to retrieve categories'),
                'errors'  => ['exception' => $e->getMessage()],
            ], 500);
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => __('Category created successfully'),
                'data'    => new CategoryResource($category->load(['parent', 'children']))
            ], 201);

        } catch (\Throwable $e) {
            Log::error('Category Store Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to create category'),
                'errors'  => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function show(Category $category)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => __('Category retrieved successfully'),
                'data'    => new CategoryResource($category->load(['parent', 'children']))
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Category Show Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to retrieve category'),
                'errors'  => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => __('Category updated successfully'),
                'data'    => new CategoryResource($category->load(['parent', 'children']))
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Category Update Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to update category'),
                'errors'  => ['exception' => $e->getMessage()]
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->vacancies()->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => __('Cannot delete category with active vacancies'),
                ], 422);
            }

            $category->delete();

            return response()->json([
                'success' => true,
                'message' => __('Category deleted successfully')
            ], 200);

        } catch (\Throwable $e) {
            Log::error('Category Delete Error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('Failed to delete category'),
                'errors'  => ['exception' => $e->getMessage()]
            ], 500);
        }
    }
}
