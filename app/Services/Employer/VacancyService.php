<?php

namespace App\Services\Employer;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\Employer\VacancyDTO;
use App\Interfaces\Services\Employer\VacancyServiceInterface;
use App\Interfaces\Repositories\Employer\VacancyRepositoryInterface;

class VacancyService implements VacancyServiceInterface
{
    public function __construct(
        protected VacancyRepositoryInterface $repository
    ) {}

    public function listVacancies(Request $request)
    {
        return $this->repository->all($request);
    }

    public function create(VacancyDTO $dto): Vacancy
    {
        return $this->repository->create($dto);
    }

    public function update(Vacancy $vacancy, VacancyDTO $dto): Vacancy
    {
        return $this->repository->update($vacancy, $dto);
    }

    public function delete(Vacancy $vacancy): void
    {
        $this->repository->delete($vacancy);
    }

    public function listApplications(Vacancy $vacancy)
    {
        return $this->repository->listApplications($vacancy);
    }

    public function toggleStatus(Vacancy $vacancy): bool
    {
        return $this->repository->toggleStatus($vacancy);
    }
}
