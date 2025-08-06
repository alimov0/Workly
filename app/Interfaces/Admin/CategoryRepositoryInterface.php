<?php

namespace App\Interfaces\Repositories\Admin;

use App\Models\Category;
use Illuminate\Http\Request;

interface CategoryRepositoryInterface
{
    public function all(Request $request);
    public function store(array $data);
    public function show(Category $category);
    public function update(Category $category, array $data);
    public function destroy(Category $category): bool;
}
