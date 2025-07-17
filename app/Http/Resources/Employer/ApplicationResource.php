<?php

namespace App\Http\Resources\Employer;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ApplicationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'cover_letter' => $this->cover_letter,
            'status' => $this->status,
            'resume_url' => Storage::url($this->resume_file),
            'applied_at' => $this->created_at->format('M d, Y H:i'),
            'candidate' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'profile_url' => route('public.profile', $this->user->id)
            ],
            'vacancy' => [
                'id' => $this->vacancy->id,
                'title' => $this->vacancy->title,
                'slug' => $this->vacancy->slug
            ],
            'links' => [
                'view' => route('employer.applications.show', $this->id),
                'download_resume' => route('employer.applications.download', $this->id)
            ]
        ];
    }
}