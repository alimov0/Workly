<?php

namespace App\Http\Controllers\Employer;

use App\Models\Application;
use App\Http\Controllers\Controller;
use App\Http\Resources\Employer\ApplicationResource;
use App\Http\Requests\UpdateApplicationStatusRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
class ApplicationController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'employer']);
    }

    public function show(Application $application)
    {
        $this->authorize('view', $application->vacancy);

        return $this->success(
            new ApplicationResource($application->load(['user', 'vacancy'])),
            'Ariza topildi'
        );
    }

    public function updateStatus(UpdateApplicationStatusRequest $request, Application $application)
    {
        $this->authorize('update', $application->vacancy);

        $application->update(['status' => $request->status]);

        return $this->success(
            new ApplicationResource($application),
            'Status muvaffaqiyatli yangilandi'
        );
    }

    public function download(Application $application)
    {
        // Vakansiya egasi ekanini tekshir
        $this->authorize('view', $application->vacancy);
    
        // Fayl borligini tekshir
        if (!Storage::exists($application->resume_file)) {
            return $this->errorResponse('CV fayli topilmadi.', Response::HTTP_NOT_FOUND);
        }
    
        // Faylni yuklab berish
        return Storage::download($application->resume_file);
}
}