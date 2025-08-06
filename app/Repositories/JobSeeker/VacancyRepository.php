<?php 

namespace App\Repositories\JobSeeker;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\JobSeeker\VacancyDTO;
use App\Interfaces\Repositories\JobSeeker\VacancyRepositoryInterface;

class VacancyRepository implements VacancyRepositoryInterface
{
    public function getVacancies(Request $request)
    {
        $query = Vacancy::query()
            ->with(['user', 'category'])
            ->where('is_active', true);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_salary')) {
            $query->where('salary_from', '>=', $request->min_salary);
        }

        if ($request->filled('max_salary')) {
            $query->where('salary_to', '<=', $request->max_salary);
        }

        $query->orderBy(
            $request->get('sort_field', 'created_at'),
            $request->get('sort_order', 'desc')
        );

        return $query->paginate($request->get('per_page', 15));
    }

    public function findBySlug(string $slug): ?Vacancy
    {
        return Vacancy::where('slug', $slug)
            ->with(['user', 'category'])
            ->where('is_active', true)
            ->first();
    }

    public function ToVacancy(int $id, VacancyDTO $dto)
    {
        $vacancy = Vacancy::find($id);

        if (!$vacancy) {
            return null;
        }

        return $vacancy->applications()->create([
            'user_id' => $dto->user_id,
            'cover_letter' => $dto->cover_letter,
        ]);
    }
}
