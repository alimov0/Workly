<?php 
namespace App\Interfaces\Repositories\JobSeeker;

use App\Models\Application;
use App\Models\Vacancy;
use App\DTO\JobSeeker\ApplicationDTO;

interface ApplicationRepositoryInterface
{
    public function listUserApplications($user);
    public function store(Vacancy $vacancy, ApplicationDTO $dto): Application;
    public function delete(Application $application): void;
}
