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
                'status' => 'success',
                'message' => 'Vakansiyaga yuborilgan arizalar ro‘yxati',
                'data' => ApplicationResource::collection($applications)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Arizalarni olishda xatolik yuz berdi.',
                
            ]);
        }
    }

    public function show(Application $application): JsonResponse
    {
        try {
            $this->authorize('view', $application->vacancy);

            return response()->json([
                'status' => 'success',
                'message' => 'Ariza tafsilotlari',
                'data' => new ApplicationResource($application->load(['user', 'vacancy']))
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Arizani ko‘rishda xatolik yuz berdi.',
        
            ]);
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
                'status' => 'success',
                'message' => 'Ariza holati yangilandi',
                'data' => new ApplicationResource($application)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Statusni yangilashda xatolik yuz berdi.',
            
            ]);
        }
    }

    public function download(Application $application)
    {
        try {
            $this->authorize('view', $application->vacancy);

            if (!Storage::disk('public')->exists($application->resume_file)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'CV fayli topilmadi.'
                ]);
            }

            return Storage::download($application->resume_file);
        } catch (\Throwable $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'CV faylini yuklashda xatolik.',
            ]);
        }
    }
}
