<?php

namespace App\Http\Controllers\JobSeeker;

use Throwable;
use App\Models\Vacancy;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\DTO\JobSeeker\ApplicationDTO;
use App\Http\Requests\ApplicationRequest;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Resources\JobSeeker\ApplicationResource;
use App\Interfaces\Services\JobSeeker\ApplicationServiceInterface;

class ApplicationController extends Controller
{
    protected ApplicationServiceInterface $service;

    public function __construct(ApplicationServiceInterface $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        try {
            $applications = $this->service->listUserApplications($request);

            return response()->json([
                'status' => 'success',
                'message' => __('Arizalar muvaffaqiyatli olindi'),
                'data' => ApplicationResource::collection($applications)
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Arizalarni olishda xatolik yuz berdi'));
        }
    }

    public function store(ApplicationRequest $request, Vacancy $vacancy)
    {
        try {
            $dto = ApplicationDTO::fromRequest($request);
            $application = $this->service->store($dto, $vacancy);

            return response()->json([
                'status' => 'success',
                'message' => __('Ariza muvaffaqiyatli yuborildi'),
                'data' => new ApplicationResource($application->load('vacancy'))
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Ariza yuborishda xatolik yuz berdi'));
        }
    }

    public function destroy(Application $application)
    {
        try {
            $this->authorize('delete', $application);

            $this->service->delete($application);

            return response()->json([
                'status' => 'success',
                'message' => __('Ariza bekor qilindi')
            ]);
        } catch (Throwable $e) {
            return $this->errorResponse($e, __('Arizani o‘chirishda xatolik yuz berdi'));
        }
    }

    protected function errorResponse(Throwable $e, string $message, int $status = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => config('app.debug') ? $e->getMessage() : null
        ], $status);
    }
}
