<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacancyRequest;
use App\Http\Resources\Employer\VacancyResource;
use App\Http\Resources\Employer\ApplicationResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $vacancies = $request->user()
                ->vacancies()
                ->with(['category', 'applications'])
                ->latest()
                ->paginate($request->per_page ?? 10);

            return response()->json([
                'success' => true,
                'message' => 'Vakansiyalar ro‘yxati olindi',
                'data' => VacancyResource::collection($vacancies),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyalarni olishda xatolik yuz berdi.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function store(VacancyRequest $request)
    {
        try {
            $vacancy = $request->user()
                ->vacancies()
                ->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Vakansiya muvaffaqiyatli yaratildi',
                'data' => new VacancyResource($vacancy->load('category')),
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyani yaratishda xatolik yuz berdi.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function update(VacancyRequest $request, Vacancy $vacancy)
    {
        try {
            $this->authorize('update', $vacancy);

            $vacancy->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Vakansiya yangilandi',
                'data' => new VacancyResource($vacancy->load('category')),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyani yangilashda xatolik yuz berdi.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function destroy(Vacancy $vacancy)
    {
        try {
            $this->authorize('delete', $vacancy);

            $vacancy->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vakansiya o‘chirildi',
            ], 204);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyani o‘chirishda xatolik yuz berdi.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function applications(Vacancy $vacancy)
    {
        try {
            $this->authorize('view', $vacancy);

            $applications = $vacancy->applications()
                ->with('user')
                ->latest()
                ->paginate();

            return response()->json([
                'success' => true,
                'message' => 'Arizalar ro‘yxati',
                'data' => ApplicationResource::collection($applications),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arizalarni olishda xatolik yuz berdi.',
                'error' => $e->getMessage(),
            ]);
        }
    }

    public function toggleStatus(Vacancy $vacancy)
    {
        try {
            $this->authorize('update', $vacancy);

            $vacancy->update([
                'is_active' => !$vacancy->is_active
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vakansiya holati yangilandi',
                'data' => ['is_active' => $vacancy->is_active],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Holatni o‘zgartirishda xatolik yuz berdi.',
                'error' => $e->getMessage(),
            ]);
        }
    }
}
