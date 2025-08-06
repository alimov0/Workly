<?php

namespace App\Services\Admin\Interfaces;

use Illuminate\Http\Request;

interface ApplicationServiceInterface
{
    public function index(Request $request);

    public function show($id);

    public function store(array $data);

    public function update($id, array $data);

    public function destroy($id);
}
