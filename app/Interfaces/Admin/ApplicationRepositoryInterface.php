<?php

namespace App\Interfaces\Admin;

use App\DTO\Admin\ApplicationDTO;

interface ApplicationRepositoryInterface
{
    public function getAllApplications(ApplicationDTO $dto);
}
