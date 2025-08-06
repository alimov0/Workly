<?php

namespace App\DTO\Admin;

class CategoryDTO
{
    public function __construct(
        public string $title,
        public ?string $description = null,
        public ?int $parent_id = null,
    ) {}

    public static function fromRequest( $data): self
    {
        return new self(
             $data['title'],
             $data['description'] ?? null,
             $data['parent_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'title' => $this->title,
            'description' => $this->description,
            'parent_id' => $this->parent_id,
        ];
    }
}
