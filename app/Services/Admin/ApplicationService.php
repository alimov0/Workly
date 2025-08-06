<?php

namespace App\Services\Admin;

use App\Models\Application;
use Illuminate\Http\Request;
use App\DTO\Admin\ApplicationDTO;
use App\Interfaces\Admin\ApplicationRepositoryInterface;
use App\Services\Admin\Interfaces\ApplicationServiceInterface;

class ApplicationService implements ApplicationServiceInterface
{
    protected $applicationRepository;

    public function __construct(ApplicationRepositoryInterface $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function getAllApplications(ApplicationDTO $dto)
    {
        return $this->applicationRepository->getAllApplications($dto);
   
    }
    public function index(Request $request)
    {
        return Application::paginate(10);
    }

    public function show($id)
    {
        return Application::findOrFail($id);
    }

    public function store(array $data)
    {
        return Application::create($data);
    }

    public function update($id, array $data)
    {
        $application = Application::findOrFail($id);
        $application->update($data);
        return $application;
    }

    public function destroy($id)
    {
        $application = Application::findOrFail($id);
        $application->delete();
        return true;
    }



}
