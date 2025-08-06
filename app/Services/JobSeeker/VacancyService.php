<?php
namespace App\Services\JobSeeker;

use Illuminate\Http\Request;
use App\DTO\JobSeeker\VacancyDTO;
use App\Interfaces\Services\JobSeeker\VacancyServiceInterface;
use App\Interfaces\Repositories\JobSeeker\VacancyRepositoryInterface;

    class VacancyService implements VacancyServiceInterface
{
    public function __construct(
        protected VacancyRepositoryInterface $repository
    ) {}

    public function getVacancies(Request $request)
    {
        return $this->repository->getVacancies($request);
    }

    public function getVacancyBySlug(string $slug)
    {
        return $this->repository->findBySlug($slug);
    }

    public function ToVacancy(int $id, Request $request)
    {
        $dto = VacancyDTO::fromRequest($request);

        return $this->repository->applyToVacancy($id, $dto);
    }
}
