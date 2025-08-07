<?php

namespace App\Services\Admin;

use App\Interfaces\Admin\ApplicationRepositoryInterface;
use App\Services\Admin\Interfaces\ApplicationServiceInterface;

class ApplicationService implements ApplicationServiceInterface
{
    protected ApplicationRepositoryInterface $applicationRepository;

    public function __construct(ApplicationRepositoryInterface $applicationRepository)
    { 
        $this->applicationRepository = $applicationRepository;
    }

    public function listPaginated(int $perPage = 10)
    {
        return $this->applicationRepository->paginate($perPage);
    }

    public function show(int $id)
    {
        return $this->applicationRepository->find($id);
    }

    public function store(array $data)
    {
        return $this->applicationRepository->create($data);
    }

    public function update(int $id, array $data)
    {
        return $this->applicationRepository->update($id, $data);
    }

    public function destroy(int $id)
    {
        return $this->applicationRepository->delete($id);
    }
}
