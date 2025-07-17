<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notification;
use App\Notifications\NewVacancyNotification;

class VacancyObserver
{
    public function creating(Vacancy $vacancy)
    {
        $vacancy->slug = Str::slug($vacancy->title) . '-' . Str::random(6);
    }
    
    public function created(Vacancy $vacancy)
    {
        if ($vacancy->user->isEmployer()) {
            Notification::send(
                User::where('role', 'admin')->get(),
                new NewVacancyNotification($vacancy)
            );
        }
    }
}
