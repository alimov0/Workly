<?php

namespace App\Interfaces\Admin;

use App\Models\Application;

interface ApplicationRepositoryInterface
{
    public function paginate(int $perPage = 10);

    public function find(int $id): Application;

    public function create(array $data): Application;

    public function update(int $id, array $data): Application;

    public function delete(int $id): bool;
}
