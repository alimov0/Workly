<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\VacancyResource;
use App\Http\Requests\Admin\VacancyStoreRequest;
use App\Http\Requests\Admin\VacancyUpdateRequest;

class VacancyController extends Controller
{
    use \App\Traits\ApiResponse;

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }

    // Barcha vakansiyalar
    public function index(Request $request)
    {
        $query = Vacancy::with(['user', 'category']);

        if ($request->has('search')) {
            $query->where('title', 'like', '%'.$request->search.'%')
                ->orWhere('description', 'like', '%'.$request->search.'%');
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $query->orderBy('created_at', 'desc');
        $data = VacancyResource::collection($query->paginate($request->per_page ?? 15));

        return $this->success($data, 'Vakansiyalar ro‘yxati');
    }

    // Yangi vakansiya
    public function store(VacancyStoreRequest $request)
    {
        $vacancy = Vacancy::create($request->validated());
        return $this->success(new VacancyResource($vacancy->load(['user', 'category'])), 'Vakansiya yaratildi', 201);
    }

    // Vakansiyani ko‘rsatish
    public function show(Vacancy $vacancy)
    {
        return $this->success(new VacancyResource($vacancy->load(['user', 'category', 'applications'])), 'Vakansiya tafsilotlari');
    }

    // Yangilash
    public function update(VacancyUpdateRequest $request, Vacancy $vacancy)
    {
        $vacancy->update($request->validated());
        return $this->success(new VacancyResource($vacancy->load(['user', 'category'])), 'Vakansiya yangilandi');
    }

    // O‘chirish
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();
        return $this->noContent('Vakansiya o‘chirildi');
    }
}
