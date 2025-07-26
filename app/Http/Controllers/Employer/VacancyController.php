<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacancyRequest;
use App\Http\Resources\Employer\VacancyResource;
use App\Http\Resources\Employer\ApplicationResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'employer']);
    }
    public function index(Request $request)
    {
        $vacancies = $request->user()
            ->vacancies()
            ->with(['category', 'applications'])
            ->latest()
            ->paginate($request->per_page ?? 10);

        return $this->success(
            VacancyResource::collection($vacancies),
            'Vacancies retrieved successfully'
        );
    }
    public function store(VacancyRequest $request)
    {
        $vacancy = $request->user()
            ->vacancies()
            ->create($request->validated());

        return $this->success(
            new VacancyResource($vacancy->load('category')),
            'Vacancy created successfully',
            201
        );
    }

    public function update(VacancyRequest $request, Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $vacancy->update($request->validated());

        return $this->success(
            new VacancyResource($vacancy->load('category')),
            'Vacancy updated successfully'
        );
    }
    public function destroy(Vacancy $vacancy)
    {
        $this->authorize('delete', $vacancy);

        $vacancy->delete();

        return $this->success(null, 'Vacancy deleted successfully', 204);
    }
    public function applications(Vacancy $vacancy)
    {
        $this->authorize('view', $vacancy);

        $applications = $vacancy->applications()
            ->with('user')
            ->latest()
            ->paginate();

        return $this->success(
            ApplicationResource::collection($applications),
            'Applications retrieved successfully'
        );
    }

    public function toggleStatus(Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $vacancy->update(['is_active' => !$vacancy->is_active]);

        return $this->success([
            'is_active' => $vacancy->is_active
        ], 'Vacancy status updated');
    }
}
