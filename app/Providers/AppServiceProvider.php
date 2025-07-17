<?php

namespace App\Providers;

use App\Models\Vacancy;
use App\Models\Category;
use App\Observers\VacancyObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
