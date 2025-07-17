<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Auth\Access\Response;

class VacancyPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Vacancy $vacancy)
    {
        return $user->id === $vacancy->user_id;
    }

    public function delete(User $user, Vacancy $vacancy)
    {
        return $user->id === $vacancy->user_id;
    }

    public function view(User $user, Vacancy $vacancy)
    {
        return $user->id === $vacancy->user_id;
    }
}

