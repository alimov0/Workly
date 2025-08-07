<?php

namespace App\Services\Admin\Interfaces;

interface ApplicationServiceInterface
{
    public function listPaginated(int $perPage = 10);

    public function show(int $id);

    public function store(array $data);

    public function update(int $id, array $data);

    public function destroy(int $id);
}
