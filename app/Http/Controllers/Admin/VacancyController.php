<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\VacancyStoreRequest;
use App\Http\Requests\Admin\VacancyUpdateRequest;
use App\Http\Resources\Admin\VacancyResource;
use App\Traits\HttpResponses;

class VacancyController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'admin']);
    }
    public function index(Request $request)
    {
        $query = Vacancy::with(['user', 'category']);

        if ($request->has('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->is_active);
        }

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $query->orderBy('created_at', 'desc');

        $vacancies = $query->paginate($request->per_page ?? 15);

        return $this->successResponse(VacancyResource::collection($vacancies), 'Vakansiyalar ro‘yxati');
    }
    public function store(VacancyStoreRequest $request)
    {
        $vacancy = Vacancy::create($request->validated());

        return $this->successResponse(
            new VacancyResource($vacancy->load(['user', 'category'])),
            'Vakansiya yaratildi',
            201
        );
    }
    public function show(Vacancy $vacancy)
    {
        return $this->successResponse(
            new VacancyResource($vacancy->load(['user', 'category', 'applications'])),
            'Vakansiya tafsilotlari'
        );
    }
    public function update(VacancyUpdateRequest $request, Vacancy $vacancy)
    {
        $vacancy->update($request->validated());

        return $this->successResponse(
            new VacancyResource($vacancy->load(['user', 'category'])),
            'Vakansiya yangilandi'
        );
    }
    public function destroy(Vacancy $vacancy)
    {
        $vacancy->delete();

        return $this->successResponse(null, 'Vakansiya o‘chirildi', 204);
    }
}
