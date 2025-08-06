<?php
 namespace App\Services\JobSeeker;

 use Illuminate\Http\Request;
 use App\Models\Application;
 use App\Models\Vacancy;
 use App\Jobs\SendApplicationEmail;
 use Illuminate\Support\Facades\Storage;
 use App\DTO\JobSeeker\ApplicationDTO;
 use App\Interfaces\Services\JobSeeker\ApplicationServiceInterface;
 use App\Interfaces\Repositories\JobSeeker\ApplicationRepositoryInterface;
 
 class ApplicationService implements ApplicationServiceInterface
 {
     public function __construct(
         protected ApplicationRepositoryInterface $repository
     ) {}
 
     public function listUserApplications(Request $request)
     {
         return $this->repository->listUserApplications($request->user());
     }
 
     public function store(Request $request, Vacancy $vacancy): Application
     {
         if (!$vacancy->is_active || $vacancy->deadline < now()) {
             throw new \Exception('Bu vakansiyaga ariza topshirib bo‘lmaydi');
         }
 
         if ($request->user()->applications()->where('vacancy_id', $vacancy->id)->exists()) {
             throw new \Exception('Siz bu vakansiyaga allaqachon ariza topshirgansiz');
         }
 
         $resumePath = $request->file('resume')->store('resumes', 'public');
 
         $dto = ApplicationDTO::fromRequest($request, $resumePath);
 
         $application = $this->repository->store($vacancy, $dto);
 
         SendApplicationEmail::dispatch($application);
 
         return $application;
     }
 
     public function delete(Application $application): void
     {
         if ($application->resume_file) {
             Storage::disk('public')->delete($application->resume_file);
         }
 
         $this->repository->delete($application);
     }
 }
 