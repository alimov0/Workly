<?php

namespace App\Interfaces\Services\Employer;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\Employer\VacancyDTO;

interface VacancyServiceInterface
{
    public function listVacancies(Request $request);

    public function create(VacancyDTO $dto): Vacancy;

    public function update(Vacancy $vacancy, VacancyDTO $dto): Vacancy;

    public function delete(Vacancy $vacancy): void;

    public function listApplications(Vacancy $vacancy);

    public function toggleStatus(Vacancy $vacancy): bool;
}
