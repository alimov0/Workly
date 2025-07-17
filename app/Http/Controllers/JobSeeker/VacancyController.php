<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobSeeker\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    /**
     * Barcha aktiv vakansiyalarni qaytarish
     */
    public function index(Request $request)
    {
        $query = Vacancy::query()
            ->with(['user', 'category'])
            ->latest();

        // Qidiruv funksiyasi
        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Filtrlash
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('min_salary')) {
            $query->where('salary_from', '>=', $request->min_salary);
        }

        if ($request->has('max_salary')) {
            $query->where('salary_to', '<=', $request->max_salary);
        }

        // Saralash
        $sortField = $request->sort_field ?? 'created_at';
        $sortOrder = $request->sort_order ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        return response()->json([
            'success' => true,
            'message' => 'Vakansiyalar ro‘yxati muvaffaqiyatli olindi.',
            'data' => VacancyResource::collection($query->paginate($request->per_page ?? 15))
        ]);
    }

    /**
     * Slug orqali bitta vakansiyani ko'rsatish
     */
    public function show($slug)
    {
        $vacancy = Vacancy::where('slug', $slug)
            ->with(['user', 'category'])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Vakansiya maʼlumotlari muvaffaqiyatli olindi.',
            'data' => new VacancyResource($vacancy)
        ]);
    }
}
