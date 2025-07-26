<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\JobSeeker\ApplicationResource;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Jobs\SendApplicationEmail;

class ApplicationController extends Controller
{
    /**
     * Foydalanuvchining barcha arizalarini ko‘rsatish
     */
    public function index(Request $request)
    {
        try {
            $applications = $request->user()
                ->applications()
                ->with(['vacancy', 'vacancy.user'])
                ->latest()
                ->paginate();

            return response()->json([
                'success' => true,
                'message' => 'Arizalar muvaffaqiyatli olindi',
                'data' => ApplicationResource::collection($applications)
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arizalarni olishda xatolik yuz berdi',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Yangi ariza yaratish
     */
    public function store(ApplicationRequest $request, Vacancy $vacancy)
    {
        try {
            if (!$vacancy->is_active || $vacancy->deadline < now()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bu vakansiyaga ariza topshirib bo‘lmaydi'
                ], 400);
            }

            if ($request->user()->applications()->where('vacancy_id', $vacancy->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Siz bu vakansiyaga allaqachon ariza topshirgansiz'
                ], 400);
            }

            $resumePath = $request->file('resume')->store('resumes', 'public');

            $application = $vacancy->applications()->create([
                'user_id' => $request->user()->id,
                'cover_letter' => $request->cover_letter,
                'resume_file' => $resumePath,
                'status' => 'pending',
            ]);

            SendApplicationEmail::dispatch($application);

            return response()->json([
                'success' => true,
                'message' => 'Ariza muvaffaqiyatli yuborildi',
                'data' => new ApplicationResource($application->load(['vacancy']))
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ariza yuborishda xatolik yuz berdi',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Arizani bekor qilish
     */
    public function destroy(Application $application)
    {
        try {
            $this->authorize('delete', $application);

            if ($application->resume_file) {
                Storage::disk('public')->delete($application->resume_file);
            }

            $application->delete();

            return response()->json([
                'success' => true,
                'message' => 'Ariza bekor qilindi'
            ], 204);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Arizani o‘chirishda xatolik yuz berdi',
                'error' => $e->getMessage()
            ]);
        }
    }
}
