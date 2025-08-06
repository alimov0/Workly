<?php

namespace App\Services\Admin;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\Admin\VacancyDTO;
use App\Interfaces\Admin\VacancyServiceInterface;
use App\Interfaces\Admin\VacancyRepositoryInterface;

class VacancyService implements VacancyServiceInterface
{
    public function __construct(
        protected VacancyRepositoryInterface $repository
    ) {}

    public function getAll(Request $request)
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

    public function show(Vacancy $vacancy): Vacancy
    {
        return $this->repository->find($vacancy);
    }
}
