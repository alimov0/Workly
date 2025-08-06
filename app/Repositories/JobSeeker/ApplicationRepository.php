<?php 
namespace App\Repositories\JobSeeker;

use App\Models\Application;
use App\Models\Vacancy;
use App\DTO\JobSeeker\ApplicationDTO;
use App\Interfaces\Repositories\JobSeeker\ApplicationRepositoryInterface;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function listUserApplications($user)
    {
        return $user->applications()
            ->with(['vacancy', 'vacancy.user'])
            ->latest()
            ->paginate();
    }

    public function store(Vacancy $vacancy, ApplicationDTO $dto): Application
    {
        return $vacancy->applications()->create([
            'user_id' => $dto->user_id,
            'cover_letter' => $dto->cover_letter,
            'resume_file' => $dto->resume_path,
            'status' => 'pending',
        ]);
    }

    public function delete(Application $application): void
    {
        $application->delete();
    }
}
