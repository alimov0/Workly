<?php

namespace App\Repositories;

use App\Models\Vacancy;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function getByVacancy(int $vacancyId)
    {
        $vacancy = Vacancy::where('user_id', Auth::id())->findOrFail($vacancyId);

        return $vacancy->applications()
            ->with('user')
            ->latest()
            ->get();
    }

    public function loadRelations(Application $application): Application
    {
        return $application->load(['user', 'vacancy']);
    }

    public function updateStatus(Application $application, string $status): Application
    {
        $application->update(['status' => $status]);
        return $application;
    }

    public function downloadResume(Application $application)
    {
        $path = $application->resume_file;

        if (!Storage::disk('public')->exists($path)) {
            return null;
        }

        return Storage::download($path);
    }
}
