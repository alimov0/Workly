<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\CategoryResource;
use App\Http\Requests\Admin\CategoryStoreRequest;
use App\Http\Requests\Admin\CategoryUpdateRequest;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    public function index(Request $request)
    {
        $query = Category::with(['parent', 'children']);

        if ($request->has('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->has('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        return response()->json([
            'message' => __('Categories retrieved successfully'),
            'data' => CategoryResource::collection($query->paginate($request->per_page ?? 15)),
        ]);
    }

    public function store(CategoryStoreRequest $request)
    {
        $category = Category::create($request->validated());
        return response()->json([
            'message' => __('Category created successfully'),
            'data' => new CategoryResource($category->load(['parent', 'children']))
        ], 201);
    }

    public function show(Category $category)
    {
        return response()->json([
            'message' => __('Category retrieved successfully'),
            'data' => new CategoryResource($category->load(['parent', 'children']))
        ]);
    }

    public function update(CategoryUpdateRequest $request, Category $category)
    {
        $category->update($request->validated());
        return response()->json([
            'message' => __('Category updated successfully'),
            'data' => new CategoryResource($category->load(['parent', 'children']))
        ]);
    }

    public function destroy(Category $category)
    {
        if ($category->vacancies()->exists()) {
            return response()->json([
                'message' => __('Cannot delete category with active vacancies')
            ], 422);
        }

        $category->delete();
        return response()->json([
            'message' => __('Category deleted successfully')
        ], 204);
    }
}
