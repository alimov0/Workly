<?php

namespace App\Http\Resources\JobSeeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'location' => $this->location,
            'salary' => [
                'from' => $this->salary_from,
                'to' => $this->salary_to,
                'formatted' => '$'.number_format($this->salary_from).' - $'.number_format($this->salary_to)
            ],
            'deadline' => $this->deadline->format('M d, Y'),
            'employer' => [
                'name' => $this->user->name,
                'company' => $this->user->company_name // Agar employerda company_name maydoni bo'lsa
            ],
            'category' => $this->category->title,
            'applied' => $this->when(
                $request->user(), 
                $request->user()->applications()->where('vacancy_id', $this->id)->exists()
            ),
            'meta' => [
                'days_remaining' => now()->diffInDays($this->deadline, false)
            ]
        ];
    }
}
