<?php

namespace App\Interfaces\Admin;

use App\Models\Vacancy;
use App\DTO\Admin\VacancyDTO;
use Illuminate\Http\Request;

interface VacancyServiceInterface
{
    public function getAll(Request $request);
    public function create(VacancyDTO $dto): Vacancy;
    public function update(Vacancy $vacancy, VacancyDTO $dto): Vacancy;
    public function delete(Vacancy $vacancy): void;
    public function show(Vacancy $vacancy): Vacancy;
}
