<?php

namespace App\Services\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use App\DTO\Admin\CategoryDTO;
use App\Interfaces\Services\Admin\CategoryServiceInterface;
use App\Interfaces\Repositories\Admin\CategoryRepositoryInterface;

class CategoryService implements CategoryServiceInterface
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        return $this->categoryRepository->all($request);
    }

    public function store(CategoryDTO $dto)
    {
        return $this->categoryRepository->store($dto->toArray());
    }

    public function show(Category $category)
    {
        return $this->categoryRepository->show($category);
    }

    public function update(Category $category, CategoryDTO $dto)
    {
        return $this->categoryRepository->update($category, $dto->toArray());
    }

    public function destroy(Category $category): bool
    {
        return $this->categoryRepository->destroy($category);
    }
}
