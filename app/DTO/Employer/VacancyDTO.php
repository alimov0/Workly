<?php

namespace App\DTO\Employer;

use Illuminate\Http\Request;

class VacancyDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public int $category_id,
        public bool $is_active = true
    ) {}

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('title'),
            $request->input('description'),
            $request->input('category_id'),
            $request->boolean('is_active', true)
        );
    }
}
