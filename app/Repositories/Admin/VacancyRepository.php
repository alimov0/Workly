<?php

namespace App\Repositories\Admin;

use App\Models\Vacancy;
use App\DTO\Admin\VacancyDTO;
use Illuminate\Http\Request;
use App\Interfaces\Admin\VacancyRepositoryInterface;

class VacancyRepository implements VacancyRepositoryInterface
{
    public function all(Request $request)
    {
        return Vacancy::with(['user', 'category'])->latest()->get();
    }

    public function create(VacancyDTO $dto): Vacancy
    {
        return Vacancy::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
            'user_id' => $dto->user_id,
            'is_active' => $dto->is_active,
        ]);
    }

    public function update(Vacancy $vacancy, VacancyDTO $dto): Vacancy
    {
        $vacancy->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
            'user_id' => $dto->user_id,
            'is_active' => $dto->is_active,
        ]);

        return $vacancy;
    }

    public function delete(Vacancy $vacancy): void
    {
        $vacancy->delete();
    }

    public function find(Vacancy $vacancy): Vacancy
    {
        return $vacancy->load(['user', 'category']);
    }
}
