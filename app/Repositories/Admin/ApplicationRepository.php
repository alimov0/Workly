<?php

namespace App\Repositories\Admin;

use App\Models\Application;
use App\DTO\Admin\ApplicationDTO;
use App\Interfaces\Admin\ApplicationRepositoryInterface;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function getAllApplications(ApplicationDTO $dto)
    {
        $query = Application::with(['user', 'vacancy']);

        if ($dto->status) {
            $query->where('status', $dto->status);
        }

        if ($dto->user_id) {
            $query->where('user_id', $dto->user_id);
        }

        if ($dto->vacancy_id) {
            $query->where('vacancy_id', $dto->vacancy_id);
        }

        return $query->paginate($dto->per_page ?? 15);
    }
}
