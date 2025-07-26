<?php

namespace App\Http\Controllers\Employer;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateApplicationStatusRequest;
use App\Http\Resources\Employer\ApplicationResource;
use App\Models\Application;
use App\Models\Vacancy;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ApplicationController extends Controller
{
    use HttpResponses;

    public function __construct()
    {
        $this->middleware(['auth:sanctum', 'employer']);
    }
    public function index($vacancyId)
    {
        $vacancy = Vacancy::where('user_id', Auth::id())->findOrFail($vacancyId);

        $applications = $vacancy->applications()->with('user')->get();

        return $this->success(
            ApplicationResource::collection($applications),
            'Applications for vacancy'
        );
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

        $application->update([
            'status' => $request->status,
        ]);

        return $this->success(
            new ApplicationResource($application),
            'Status muvaffaqiyatli yangilandi'
        );
    }

    public function download(Application $application)
    {
        $this->authorize('view', $application->vacancy);

        if (!Storage::disk('public')->exists($application->resume_file)) {
            return $this->error(null, 'CV fayli topilmadi.', Response::HTTP_NOT_FOUND);
        }

        return Storage::download($application->resume_file);
    }
}
