<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Http\Resources\Employer\ApplicationResource;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{

    public function index($vacancyId): JsonResponse
    {
        try {
            $vacancy = Vacancy::where('user_id', Auth::id())->findOrFail($vacancyId);

            $applications = $vacancy->applications()
                ->with('user')
                ->orderByDesc('created_at')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Vakansiyaga yuborilgan arizalar ro‘yxati',
                'data' => ApplicationResource::collection($applications)
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arizalarni olishda xatolik yuz berdi.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Application $application): JsonResponse
    {
        try {
            $this->authorize('view', $application->vacancy);

            return response()->json([
                'success' => true,
                'message' => 'Ariza tafsilotlari',
                'data' => new ApplicationResource($application->load(['user', 'vacancy']))
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arizani ko‘rishda xatolik yuz berdi.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application): JsonResponse
    {
        try {
            $this->authorize('update', $application->vacancy);

            $application->update([
                'status' => $request->validated()['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ariza holati yangilandi',
                'data' => new ApplicationResource($application)
            ], Response::HTTP_OK);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Statusni yangilashda xatolik yuz berdi.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function download(Application $application)
    {
        try {
            $this->authorize('view', $application->vacancy);

            if (!Storage::disk('public')->exists($application->resume_file)) {
                return response()->json([
                    'success' => false,
                    'message' => 'CV fayli topilmadi.'
                ], Response::HTTP_NOT_FOUND);
            }

            return Storage::download($application->resume_file);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'CV faylini yuklashda xatolik.',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
