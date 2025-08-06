<?php

namespace App\Interfaces\Repositories\Employer;

use App\Models\Vacancy;
use Illuminate\Http\Request;
use App\DTO\Employer\VacancyDTO;

interface VacancyRepositoryInterface
{
    public function all(Request $request);

    public function create(VacancyDTO $dto): Vacancy;

    public function update(Vacancy $vacancy, VacancyDTO $dto): Vacancy;

    public function delete(Vacancy $vacancy): void;

    public function listApplications(Vacancy $vacancy);

    public function toggleStatus(Vacancy $vacancy): bool;
}
