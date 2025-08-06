<?php

namespace App\Providers;

use App\Models\Vacancy;
use App\Models\Category;
use App\Services\AuthService;
use App\Observers\VacancyObserver;
use App\Observers\CategoryObserver;
use App\Services\Admin\UserService;
use App\Repositories\AuthRepository;
use App\Services\Admin\CategoryService;
use Illuminate\Support\ServiceProvider;
use App\Repositories\Admin\UserRepository;
use App\Services\Admin\ApplicationService;
use App\Services\JobSeeker\VacancyService;
use App\Services\Admin\NotificationService;
use App\Repositories\Admin\VacancyRepository;
use App\Repositories\Admin\CategoryRepository;
use App\Interfaces\Services\AuthServiceInterface;
use App\Repositories\Admin\ApplicationRepository;
use App\Repositories\Admin\NotificationRepository;
use App\Interfaces\Admin\VacancyRepositoryInterface;
use App\Interfaces\Services\Admin\UserServiceInterface;
use App\Interfaces\Admin\ApplicationRepositoryInterface;
use App\Interfaces\Repositories\AuthRepositoryInterface;
use App\Interfaces\Services\Admin\CategoryServiceInterface;
use App\Interfaces\Repositories\Admin\UserRepositoryInterface;
use App\Interfaces\Services\JobSeeker\VacancyServiceInterface;
use App\Services\Admin\Interfaces\ApplicationServiceInterface;
use App\Interfaces\Services\Admin\NotificationServiceInterface;
use App\Interfaces\Repositories\Admin\CategoryRepositoryInterface;
use App\Interfaces\Repositories\Admin\NotificationRepositoryInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
        $this->app->bind(ApplicationServiceInterface::class, ApplicationService::class);
        $this->app->bind(CategoryServiceInterface::class, CategoryService::class);
        $this->app->bind(CategoryRepositoryInterface::class, CategoryRepository::class);
        $this->app->bind(NotificationServiceInterface::class, NotificationService::class);
        $this->app->bind(NotificationRepositoryInterface::class, NotificationRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(VacancyServiceInterface::class, VacancyService::class);
        $this->app->bind(VacancyRepositoryInterface::class, VacancyRepository::class);
        $this->app->bind(ApplicationServiceInterface::class, ApplicationService::class);
       $this->app->bind(ApplicationRepositoryInterface::class, ApplicationRepository::class);
       $this->app->bind(
        \App\Interfaces\Repositories\JobSeeker\ApplicationRepositoryInterface::class,
        \App\Repositories\JobSeeker\ApplicationRepository::class
    );

    $this->app->bind(
        \App\Interfaces\Services\JobSeeker\ApplicationServiceInterface::class,
        \App\Services\JobSeeker\ApplicationService::class
    );
    $this->app->bind(
        \App\Interfaces\Repositories\JobSeeker\VacancyRepositoryInterface::class,
        \App\Repositories\JobSeeker\VacancyRepository::class
    );

    $this->app->bind(
        \App\Interfaces\Services\JobSeeker\VacancyServiceInterface::class,
        \App\Services\JobSeeker\VacancyService::class
    );
    $this->app->bind(
        AuthServiceInterface::class,
        AuthService::class
    );

    $this->app->bind(
        AuthRepositoryInterface::class,
        AuthRepository::class
    );


}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vacancy::observe(VacancyObserver::class);
        Category::observe(CategoryObserver::class);
        
    }
}
