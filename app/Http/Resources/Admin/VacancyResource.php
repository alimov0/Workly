<?php

namespace App\Http\Resources\Admin;

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
            'salary_from' => $this->salary_from,
            'salary_to' => $this->salary_to,
            'deadline' => $this->deadline->format('Y-m-d'),
            'is_active' => $this->is_active,
            'applications_count' => $this->whenCounted('applications'),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'category' => [
                'id' => $this->category->id,
                'title' => $this->category->title,
            ],
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
    }

