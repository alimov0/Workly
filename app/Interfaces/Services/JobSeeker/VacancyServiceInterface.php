<?php
namespace App\Interfaces\Services\JobSeeker;

use Illuminate\Http\Request;

interface VacancyServiceInterface
{
    public function getVacancies(Request $request);
    public function getVacancyBySlug(string $slug);
    public function ToVacancy(int $id, Request $request);
}
