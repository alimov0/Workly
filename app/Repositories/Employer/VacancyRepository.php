<?php

namespace App\Repositories\Employer;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\Employer\VacancyDTO;
use App\Interfaces\Repositories\Employer\VacancyRepositoryInterface;

class VacancyRepository implements VacancyRepositoryInterface
{
    public function all(Request $request)
    {
        return Vacancy::with(['category', 'user'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->latest()
            ->get();
    }

    public function create(VacancyDTO $dto): Vacancy
    {
        return Vacancy::create([
            'title' => $dto->title,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
            'user_id' => auth()->id(),
            'is_active' => $dto->is_active,
        ]);
    }

    public function update(Vacancy $vacancy, VacancyDTO $dto): Vacancy
    {
        $vacancy->update([
            'title' => $dto->title,
            'description' => $dto->description,
            'category_id' => $dto->category_id,
            'is_active' => $dto->is_active,
        ]);

        return $vacancy;
    }

    public function delete(Vacancy $vacancy): void
    {
        $vacancy->delete();
    }

    public function listApplications(Vacancy $vacancy)
    {
        return $vacancy->applications()->with('user')->get();
    }

    public function toggleStatus(Vacancy $vacancy): bool
    {
        $vacancy->is_active = !$vacancy->is_active;
        $vacancy->save();

        return $vacancy->is_active;
    }
}
