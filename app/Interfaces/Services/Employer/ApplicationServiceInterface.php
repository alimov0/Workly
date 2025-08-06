<?php

namespace App\Services\Employer;

use App\Models\Application;

interface ApplicationServiceInterface
{
    public function getApplicationsByVacancy(int $vacancyId);
    public function getApplicationDetails(Application $application): Application;
    public function updateStatus(Application $application, string $status): Application;
    public function downloadResume(Application $application);
}
