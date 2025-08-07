<?php

namespace App\Http\Controllers\Admin;

use Throwable;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\Admin\VacancyDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\VacancyResource;
use App\Http\Requests\Admin\VacancyStoreRequest;
use App\Http\Requests\Admin\VacancyUpdateRequest;
use App\Interfaces\Admin\VacancyServiceInterface;

class VacancyController extends Controller
{
    public function __construct(
        protected VacancyServiceInterface $vacancyService
    ) {}

    public function index(Request $request)
    {
        try {
            $vacancies = $this->vacancyService->getAll($request);
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiyalar ro‘yxati',
                'data' => VacancyResource::collection($vacancies)
            ]);
        } catch (Throwable) {
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiyalarni yuklashda xatolik',
            ], 500);
        }
    }

    public function store(VacancyStoreRequest $request)
    {
        try {
            $dto = VacancyDTO::fromArray($request->validated());
            $vacancy = $this->vacancyService->create($dto);
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiya yaratildi',
                'data' => new VacancyResource($vacancy->load(['user', 'category']))
            ], 201);
        } catch (Throwable) {
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiyani yaratishda xatolik',
            ], 500);
        }
    }

    public function show(Vacancy $vacancy)
    {
        try {
            $vacancy = $this->vacancyService->show($vacancy);
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiya tafsilotlari',
                'data' => new VacancyResource($vacancy)
            ]);
        } catch (Throwable) {
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiyani ko‘rsatishda xatolik',
            ], 500);
        }
    }

    public function update(VacancyUpdateRequest $request, Vacancy $vacancy)
    {
        try {
            $dto = VacancyDTO::fromArray($request->validated());
            $vacancy = $this->vacancyService->update($vacancy, $dto);
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiya yangilandi',
                'data' => new VacancyResource($vacancy->load(['user', 'category']))
            ]);
        } catch (Throwable) {
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiyani yangilashda xatolik',
            ], 500);
        }
    }

    public function destroy(Vacancy $vacancy)
    {
        try {
            $this->vacancyService->delete($vacancy);
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiya o‘chirildi',
            ], 204);
        } catch (Throwable) {
            return response()->json([
                'status' => 'success',
                'message' => 'Vakansiyani o‘chirishda xatolik',
            ], 500);
        }
    }
}
