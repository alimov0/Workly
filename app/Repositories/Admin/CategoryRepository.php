<?php

namespace App\Repositories\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Interfaces\Repositories\Admin\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all(Request $request)
    {
        $query = Category::with(['parent', 'children']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('parent_id')) {
            $query->where('parent_id', $request->parent_id);
        }

        return $query->paginate($request->per_page ?? 15);
    }

    public function store(array $data)
    {
        return Category::create($data)->load(['parent', 'children']);
    }

    public function show(Category $category)
    {
        return $category->load(['parent', 'children']);
    }

    public function update(Category $category, array $data)
    {
        $category->update($data);
        return $category->load(['parent', 'children']);
    }

    public function destroy(Category $category): bool
    {
        if ($category->vacancies()->exists()) {
            return false;
        }

        $category->delete();
        return true;
    }
}
