<?php

namespace App\Interfaces\Services\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\DTO\Admin\CategoryDTO;

interface CategoryServiceInterface
{
    public function index(Request $request);
    public function store(CategoryDTO $dto);
    public function show(Category $category);
    public function update(Category $category, CategoryDTO $dto);
    public function destroy(Category $category): bool;
}
