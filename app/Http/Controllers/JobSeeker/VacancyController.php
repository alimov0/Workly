<?php

namespace App\Http\Controllers\JobSeeker;

use Throwable;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\JobSeeker\VacancyDTO;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\JobSeeker\VacancyResource;
use App\Interfaces\Services\JobSeeker\VacancyServiceInterface;

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
            $vacancies = $this->service->getVacancies($request);

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiyalar muvaffaqiyatli olindi.'),
                'data' => VacancyResource::collection($vacancies)
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Vakansiyalarni olishda xatolik yuz berdi.'));
        }
    }

    public function show(string $slug)
    {
        try {
            $vacancy = $this->service->getVacancyBySlug($slug);

            if (!$vacancy) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Vakansiya topilmadi.')
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'message' => __('Vakansiya maʼlumotlari muvaffaqiyatli olindi.'),
                'data' => new VacancyResource($vacancy)
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Vakansiyani ko‘rishda xatolik yuz berdi.'));
        }
    }

    public function apply(int $id, Request $request)
    {
        try {
            $dto = VacancyDTO::fromRequest($request);

            $application = $this->service->ToVacancy($id, $dto);

            if (!$application) {
                return response()->json([
                    'status' => 'error',
                    'message' => __('Vakansiya topilmadi.')
                ], Response::HTTP_NOT_FOUND);
            }

            return response()->json([
                'status' => 'success',
                'message' => __('Ariza muvaffaqiyatli yuborildi.'),
                'data' => $application
            ], Response::HTTP_CREATED);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Ariza yuborishda xatolik yuz berdi.'));
        }
    }

    protected function errorResponse(Throwable $e, string $message, int $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'error' => config('app.debug') ? $e->getMessage() : null
        ], $status);
    }
}
