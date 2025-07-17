<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable  implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
         'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

     public function  vacancies()
     {
        return $this->hasMany(Vacancy::class);
     }

     public function applications()
     {
        return $this->hasMany(Applications::class);
     }
     public function isEmployer()
     {
        return $this->role ==='employer';
     }

     public function isjobSeeker()
     {
        return $this->role === 'user';
     }

      public function sendEmailVerificationNotification()
      {
        $this->notify(new VerifyEmailNotification);
      }








}
