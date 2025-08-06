<?php

namespace App\Repositories;

use App\Models\Application;

interface ApplicationRepositoryInterface
{
    public function getByVacancy(int $vacancyId);
    public function loadRelations(Application $application): Application;
    public function updateStatus(Application $application, string $status): Application;
    public function downloadResume(Application $application);
}
