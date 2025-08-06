<?php
namespace App\Interfaces\Repositories\JobSeeker;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\JobSeeker\VacancyDTO;

interface VacancyRepositoryInterface
{
    public function getVacancies(Request $request);
    public function findBySlug(string $slug): ?Vacancy;
    public function ToVacancy(int $id, VacancyDTO $dto);
}
