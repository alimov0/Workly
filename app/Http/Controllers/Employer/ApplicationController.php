<?php

namespace App\Http\Controllers\Employer;

use Throwable;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use App\DTO\Employer\ApplicationDTO;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\Employer\ApplicationResource;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Services\Employer\ApplicationServiceInterface;

class ApplicationController extends Controller
{
    protected ApplicationServiceInterface $service;

    public function __construct(ApplicationServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * Vakansiyaga yuborilgan barcha arizalar
     */
    public function index(string $vacancyId): JsonResponse
    {
        try {
            $applications = $this->service->getApplicationsByVacancy($vacancyId);

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiyaga yuborilgan arizalar ro‘yxati'),
                'data' => ApplicationResource::collection($applications),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Arizalarni olishda xatolik yuz berdi.'));
        }
    }

    /**
     * Bitta ariza tafsilotlari
     */
    public function show(Application $application): JsonResponse
    {
        try {
            $this->authorize('view', $application->vacancy);

            $applicationData = $this->service->getApplicationDetails($application);

            return response()->json([
                'status' => 'success',
                'message' => __('Ariza tafsilotlari'),
                'data' => new ApplicationResource($applicationData),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Arizani ko‘rishda xatolik yuz berdi.'));
        }
    }

    /**
     * Ariza holatini yangilash
     */
    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): JsonResponse
    {
        try {
            $this->authorize('update', $application->vacancy);

            $dto = ApplicationDTO::fromRequest($request);
            $updated = $this->service->updateStatus($application, $dto);

            return response()->json([
                'status' => 'success',
                'message' => __('Ariza holati yangilandi'),
                'data' => new ApplicationResource($updated),
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Statusni yangilashda xatolik yuz berdi.'));
        }
    }

    /**
     * Arizaga yuborilgan faylni yuklab olish
     */
    public function download(Application $application)
    {
        try {
            $this->authorize('view', $application->vacancy);

            $download = $this->service->downloadResume($application);

            if (!$download) {
                return response()->json([
                    'status' => 'success',
                    'message' => __('CV fayli topilmadi.'),
                ]);
            }

            return $download;
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('CV faylini yuklashda xatolik.'));
        }
    }

    /**
     * Xatoliklar uchun standart javob
     */
    protected function errorResponse(Throwable $e, string $message): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => config('app.debug') ? $e->getMessage() : null
        ]);
    }
}
