<?php

namespace App\DTO\Admin;

class VacancyDTO
{
    public function __construct(
        public string $title,
        public string $description,
        public int $category_id,
        public int $user_id,
        public bool $is_active = true
    ) {}

    public static function fromArray(array $validated): self
    {
        return new self(
            $validated['title'],
            $validated['description'],
            $validated['category_id'],
            auth()->id(), // yoki $validated['user_id']
            $validated['is_active'] ?? true
        );
    }
}
