<?php

namespace App\Http\Resources\Employer;

use Illuminate\Http\Resources\Json\JsonResource;

class VacancyResource extends JsonResource
{
    public function toArray($request)
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
                'currency' => 'USD',
                'formatted' => '$' . number_format($this->salary_from) . ' - $' . number_format($this->salary_to)
            ],
            'deadline' => $this->deadline->format('Y-m-d'),
            'is_active' => $this->is_active,
            'meta' => [
                'applications_count' => $this->whenCounted('applications'),
                'created_at' => $this->created_at->diffForHumans(),
                'days_remaining' => now()->diffInDays($this->deadline, false)
            ],
            'category' => [
                'id' => $this->category->id,
                'name' => $this->category->title,
                'slug' => $this->category->slug
            ],
            'links' => [
                'self' => route('employer.vacancies.show', $this->id),
                'applications' => route('employer.vacancies.applications', $this->id)
            ]
        ];
    }
}