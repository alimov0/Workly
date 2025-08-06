<?php

namespace App\Services\Employer;

use App\Models\Application;
use App\Repositories\ApplicationRepositoryInterface;

class ApplicationService implements ApplicationServiceInterface
{
    protected ApplicationRepositoryInterface $applicationRepo;

    public function __construct(ApplicationRepositoryInterface $applicationRepo)
    {
        $this->applicationRepo = $applicationRepo;
    }

    public function getApplicationsByVacancy(int $vacancyId)
    {
        return $this->applicationRepo->getByVacancy($vacancyId);
    }

    public function getApplicationDetails(Application $application): Application
    {
        return $this->applicationRepo->loadRelations($application);
    }

    public function updateStatus(Application $application, string $status): Application
    {
        return $this->applicationRepo->updateStatus($application, $status);
    }

    public function downloadResume(Application $application)
    {
        return $this->applicationRepo->downloadResume($application);
    }
}
