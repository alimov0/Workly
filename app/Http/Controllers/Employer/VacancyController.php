<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacancyRequest;
use App\Http\Resources\Employer\VacancyResource;
use App\Http\Resources\Employer\ApplicationResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'employer']);
    }

    /**
     * Employerning barcha vakansiyalarini qaytarish
     */
    public function index(Request $request)
    {
        $vacancies = $request->user()
            ->vacancies()
            ->with(['category', 'applications'])
            ->latest()
            ->paginate($request->per_page ?? 10);

        return $this->responseSuccess(
            VacancyResource::collection($vacancies),
            'Vacancies retrieved successfully'
        );
    }

    /**
     * Yangi vakansiya yaratish
     */
    public function store(VacancyRequest $request)
    {
        $vacancy = $request->user()->vacancies()->create($request->validated());

        return $this->responseSuccess(
            new VacancyResource($vacancy->load(['category'])),
            'Vacancy created successfully',
            201
        );
    }

    /**
     * Vakansiyani yangilash
     */
    public function update(VacancyRequest $request, Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $vacancy->update($request->validated());

        return $this->responseSuccess(
            new VacancyResource($vacancy->load(['category'])),
            'Vacancy updated successfully'
        );
    }

    /**
     * Vakansiyani o'chirish
     */
    public function destroy(Vacancy $vacancy)
    {
        $this->authorize('delete', $vacancy);

        $vacancy->delete();

        return $this->responseSuccess(null, 'Vacancy deleted successfully', 204);
    }

    /**
     * Vakansiyaga yuborilgan arizalarni ko'rish
     */
    public function applications(Vacancy $vacancy)
    {
        $this->authorize('view', $vacancy);

        $applications = $vacancy->applications()
            ->with(['user'])
            ->latest()
            ->paginate();

        return $this->responseSuccess(
            ApplicationResource::collection($applications),
            'Applications retrieved successfully'
        );
    }

    /**
     * Vakansiya holatini (faol/passiv) almashtirish
     */
    public function toggleStatus(Vacancy $vacancy)
    {
        $this->authorize('update', $vacancy);

        $vacancy->update(['is_active' => !$vacancy->is_active]);

        return $this->responseSuccess([
            'is_active' => $vacancy->is_active
        ], 'Vacancy status updated');
    }
}
