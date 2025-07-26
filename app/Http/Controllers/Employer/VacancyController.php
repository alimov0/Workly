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
                'status' => 'success',
                'message' => 'Vakansiyalar ro‘yxati olindi',
                'data' => VacancyResource::collection($vacancies),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vakansiyalarni olishda xatolik yuz berdi.',

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
                'status' => 'success',
                'message' => 'Vakansiya muvaffaqiyatli yaratildi',
                'data' => new VacancyResource($vacancy->load('category')),
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vakansiyani yaratishda xatolik yuz berdi.',
    
            ]);
        }
    }

    public function update(VacancyRequest $request, Vacancy $vacancy)
    {
        try {
            $this->authorize('update', $vacancy);

            $vacancy->update($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiya yangilandi',
                'data' => new VacancyResource($vacancy->load('category')),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vakansiyani yangilashda xatolik yuz berdi.',
            ]);
        }
    }

    public function destroy(Vacancy $vacancy)
    {
        try {
            $this->authorize('delete', $vacancy);

            $vacancy->delete();

            return response()->json([
                'status' => 'error',
                'message' => 'Vakansiya o‘chirildi',
            ], 204);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vakansiyani o‘chirishda xatolik yuz berdi.',
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
                'status' => 'success',
                'message' => 'Arizalar ro‘yxati',
                'data' => ApplicationResource::collection($applications),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Arizalarni olishda xatolik yuz berdi.',
            
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
                'status' => 'success',
                'message' => 'Vakansiya holati yangilandi',
                'data' => ['is_active' => $vacancy->is_active],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Holatni o‘zgartirishda xatolik yuz berdi.',
            ]);
        }
    }
}
