<?php

namespace App\Repositories\Admin;

use App\Models\Application;
use App\Interfaces\Admin\ApplicationRepositoryInterface;

class ApplicationRepository implements ApplicationRepositoryInterface
{
    public function paginate(int $perPage = 10)
    {
        return Application::paginate($perPage);
    }

    public function find(int $id): Application
    {
        return Application::findOrFail($id);
    }

    public function create(array $data): Application
    {
        return Application::create($data);
    }

    public function update(int $id, array $data): Application
    {
        $application = $this->find($id);
        $application->update($data);
        return $application;
    }

    public function delete(int $id): bool
    {
        $application = $this->find($id);
        return $application->delete();
    }
}
