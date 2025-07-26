<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VacancyStoreRequest;
use App\Http\Requests\Admin\VacancyUpdateRequest;
use App\Http\Resources\Admin\VacancyResource;
use Throwable;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Vacancy::with(['user', 'category']);

            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                      ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }

            if ($request->filled('is_active')) {
                $query->where('is_active', $request->is_active);
            }

            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }

            $query->orderBy('created_at', 'desc');

            $vacancies = $query->paginate($request->input('per_page', 15));

            return response()->json([
                'success' => true,
                'message' => 'Vakansiyalar ro‘yxati',
                'data'    => VacancyResource::collection($vacancies)
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyalarni yuklashda xatolik',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function store(VacancyStoreRequest $request)
    {
        try {
            $vacancy = Vacancy::create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Vakansiya yaratildi',
                'data'    => new VacancyResource($vacancy->load(['user', 'category']))
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyani yaratishda xatolik',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function show(Vacancy $vacancy)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Vakansiya tafsilotlari',
                'data'    => new VacancyResource($vacancy->load(['user', 'category', 'applications']))
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyani ko‘rsatishda xatolik',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function update(VacancyUpdateRequest $request, Vacancy $vacancy)
    {
        try {
            $vacancy->update($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Vakansiya yangilandi',
                'data'    => new VacancyResource($vacancy->load(['user', 'category']))
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyani yangilashda xatolik',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function destroy(Vacancy $vacancy)
    {
        try {
            $vacancy->delete();

            return response()->json([
                'success' => true,
                'message' => 'Vakansiya o‘chirildi',
                'data'    => null
            ], 204);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vakansiyani o‘chirishda xatolik',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
