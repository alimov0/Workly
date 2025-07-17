<?php

namespace App\Http\Controllers\JobSeeker;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApplicationRequest;
use App\Http\Resources\JobSeeker\ApplicationResource;
use App\Models\Application;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Traits\HttpResponses;
use App\Jobs\SendApplicationEmail;

class ApplicationController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'verified']);
    }
    /**
     * Foydalanuvchining barcha arizalarini ko'rsatish
     */
    public function index(Request $request)
    {
        $applications = $request->user()
            ->applications()
            ->with(['vacancy', 'vacancy.user'])
            ->latest()
            ->paginate();

        return $this->success(
            ApplicationResource::collection($applications),
            'Applications fetched successfully'
        );
    }

    /**
     * Yangi ariza yaratish
     */
    public function store(ApplicationRequest $request, Vacancy $vacancy)
    {
        if (!$vacancy->is_active || $vacancy->deadline < now()) {
            return $this->error(null, 'You cannot apply to this vacancy', 400);
        }
    
        if ($request->user()->applications()->where('vacancy_id', $vacancy->id)->exists()) {
            return $this->error(null, 'You have already applied to this vacancy', 400);
        }
    
        // Faylni yuklash
        $resumePath = $request->file('resume')->store('resumes', 'public');
    
        // Application yaratish (resume faylni shu yerda yozamiz)
        $application = $vacancy->applications()->create([
            'user_id' => $request->user()->id,
            'cover_letter' => $request->cover_letter,
            'resume_file' => $resumePath,
            'status' => 'pending',
        ]);
    
        // 📨 Email fon jobini ishga tushiramiz
        SendApplicationEmail::dispatch($application);
    
        return $this->success(
            new ApplicationResource($application->load(['vacancy'])),
            'Application submitted successfully',
            201
        );
    }

    /**
     * Arizani bekor qilish
     */
    public function destroy(Application $application)
    {
        $this->authorize('delete', $application);

        // Faylni o‘chirish
        Storage::disk('public')->delete($application->resume_file);

        $application->delete();

        return $this->success(null, 'Application cancelled successfully', 204)
    }
}
