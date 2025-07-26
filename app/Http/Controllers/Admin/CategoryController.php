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
                'status' => 'success',
                'message' => __('Categories retrieved successfully'),
                'data'    => CategoryResource::collection($categories),
            ], 200);

        } catch (\Throwable $e) {
        
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to retrieve categories'),
            ], 500);
        }
    }

    public function store(CategoryStoreRequest $request)
    {
        try {
            $category = Category::create($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => __('Category created successfully'),
                'data'    => new CategoryResource($category->load(['parent', 'children']))
            ], 201);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => __('Failed to create category'),
            ], 500);
        }
    }

    public function show(Category $category)
    {
        try {
            return response()->json([
                'status' => 'success',
                'message' => __('Category retrieved successfully'),
                'data'    => new CategoryResource($category->load(['parent', 'children']))
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to retrieve category'),
            ], 500);
        }
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => __('Category updated successfully'),
                'data'    => new CategoryResource($category->load(['parent', 'children']))
            ], 200);

        } catch (\Throwable $e) {
            
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to update category'),
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            if ($category->vacancies()->exists()) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Cannot delete category with active vacancies'),
                ], 422);
            }

            $category->delete();

            return response()->json([
                'status' => 'error',
                'message' => __('Category deleted successfully')
            ], 200);

        } catch (\Throwable $e) {
        
            return response()->json([
                'status' => 'error',
                'message' => __('Failed to delete category'),
            ], 500);
        }
    }
}
