<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
   
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'cover_letter' => $this->cover_letter,
            'resume_url' => Storage::url($this->resume_file),
            'status' => $this->status,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
            ],
            'vacancy' => [
                'id' => $this->vacancy->id,
                'title' => $this->vacancy->title,
                'employer' => $this->vacancy->user->name,
            ],
        ];
    }
}
