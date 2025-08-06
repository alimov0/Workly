<?php
namespace App\Interfaces\Services\JobSeeker;

use Illuminate\Http\Request;
use App\Models\Application;
use App\Models\Vacancy;

interface ApplicationServiceInterface
{
    public function listUserApplications(Request $request);
    public function store(Request $request, Vacancy $vacancy): Application;
    public function delete(Application $application): void;
}
