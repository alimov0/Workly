<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vacancy extends Model
{
    use HasFactory;

    protected $fillable = [

        'title',
        'sulg',
        'description',
        'location',
        'salary_from',
         'salary_to',
         'deadline',
         'is_active',
         'user_id',
         'category_id',

    ];

    protected $casts = [
      'deadline' => 'date',
      'is_active' => 'boolean',
  ];
   
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($vacancy) {
        $vacancy->slug = Str::slug($vacancy->title) . '-' . Str::random(6); 

        });
        static::created(function ($vacancy) {
          if ($vacancy->is_active) {
  
          }
      });
      }
      
      public function scopeActive($query)
    {
        return $query->where('is_active', true)
                    ->where('deadline', '>=', now());
    }
    
    
    
       public function  user()
       {
        return $this->belongsTo(User::class);
       } 

     public function  category()
    {
     return $this->belongsTo(category::class);
     }
      
      public function applications()

      {
        return $this->hasMany(Application::class);
      }

      public function getIsExpiredAttribute()
      {
          return $this->deadline->isPast();
      }
  
      public function getAverageSalaryAttribute()
      {
          return ($this->salary_from + $this->salary_to) / 2;
      }
  }









