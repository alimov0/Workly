<?php

namespace App\Http\Controllers\Employer;

use Throwable;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\Employer\VacancyDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\VacancyRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Employer\VacancyResource;
use App\Http\Resources\Employer\ApplicationResource;
use App\Interfaces\Services\Employer\VacancyServiceInterface;

class VacancyController extends Controller
{
    protected VacancyServiceInterface $service;

    public function __construct(VacancyServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $vacancies = $this->service->listVacancies($request);

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiyalar ro‘yxati olindi'),
                'data' => VacancyResource::collection($vacancies),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Vakansiyalarni olishda xatolik yuz berdi.'));
        }
    }

    public function store(VacancyRequest $request)
    {
        try {
            $dto = VacancyDTO::fromRequest($request);
            $vacancy = $this->service->create($dto);

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiya muvaffaqiyatli yaratildi'),
                'data' => new VacancyResource($vacancy->load('category')),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Vakansiyani yaratishda xatolik yuz berdi.'));
        }
    }

    public function update(VacancyRequest $request, Vacancy $vacancy)
    {
        try {
            $this->authorize('update', $vacancy);

            $dto = VacancyDTO::fromRequest($request);
            $updated = $this->service->update($vacancy, $dto);

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiya yangilandi'),
                'data' => new VacancyResource($updated->load('category')),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Vakansiyani yangilashda xatolik yuz berdi.'));
        }
    }

    public function destroy(Vacancy $vacancy)
    {
        try {
            $this->authorize('delete', $vacancy);

            $this->service->delete($vacancy);

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiya o‘chirildi'),
            ], Response::HTTP_NO_CONTENT);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Vakansiyani o‘chirishda xatolik yuz berdi.'));
        }
    }

    public function applications(Vacancy $vacancy)
    {
        try {
            $this->authorize('view', $vacancy);

            $applications = $this->service->listApplications($vacancy);

            return response()->json([
                'status' => 'success',
                'message' => __('Arizalar ro‘yxati'),
                'data' => ApplicationResource::collection($applications),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Arizalarni olishda xatolik yuz berdi.'));
        }
    }

    public function toggleStatus(Vacancy $vacancy)
    {
        try {
            $this->authorize('update', $vacancy);

            $isActive = $this->service->toggleStatus($vacancy);

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiya holati yangilandi'),
                'data' => ['is_active' => $isActive],
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Holatni o‘zgartirishda xatolik yuz berdi.'));
        }
    }

    /**
     * Exceptionlarni professional qaytarish
     */
    protected function errorResponse(Throwable $e, string $message)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null,
        ]);
    }
}
