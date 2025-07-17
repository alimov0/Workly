<?php

namespace App\Http\Resources\JobSeeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'applied_at' => $this->created_at->format('M d, Y H:i'),
            'cover_letter' => $this->cover_letter,
            'resume_url' => Storage::url($this->resume_file),
            'vacancy' => [
                'id' => $this->vacancy->id,
                'title' => $this->vacancy->title,
                'slug' => $this->vacancy->slug,
                'status' => $this->vacancy->is_active ? 'active' : 'inactive'
            ],
            'employer' => [
                'name' => $this->vacancy->user->name,
                'company' => $this->vacancy->user->company_name
            ],
            'actions' => [
                'cancel' => $this->status === 'pending' 
                    ? route('job-seeker.applications.destroy', $this->id) 
                    : null
            ]
        ];
    }
    }

