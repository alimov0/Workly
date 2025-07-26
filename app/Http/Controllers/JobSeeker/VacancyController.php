<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Http\Resources\JobSeeker\VacancyResource;
use App\Models\Vacancy;
use Illuminate\Http\Request;

class VacancyController extends Controller
{
    public function index(Request $request)
    {
        $query = Vacancy::query()
            ->with(['user', 'category'])
            ->where('is_active', true);

        // 🔍 Qidiruv
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

    
        $sortField = $request->get('sort_field', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortField, $sortOrder);

        
        $vacancies = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'success' => true,
            'message' => 'Vakansiyalar ro‘yxati muvaffaqiyatli olindi.',
            'data' => VacancyResource::collection($vacancies)
        ]);
    }
    public function show($slug)
    {
        $vacancy = Vacancy::where('slug', $slug)
            ->with(['user', 'category'])
            ->where('is_active', true)
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'message' => 'Vakansiya maʼlumotlari muvaffaqiyatli olindi.',
            'data' => new VacancyResource($vacancy)
        ]);
    }
    public function apply($id, Request $request)
    {
        $user = $request->user();

        $vacancy = Vacancy::findOrFail($id);

        $application = $vacancy->applications()->create([
            'user_id' => $user->id,
            'cover_letter' => $request->input('cover_letter'),
            // Fayl yuklash bo‘lsa, bu yerga logika qo‘shish mumkin
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ariza muvaffaqiyatli yuborildi.',
            'data' => $application
        ], 201);
    }
}
