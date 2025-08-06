<?php

namespace App\DTO\Admin;

class ApplicationDTO
{
    public function __construct(
        public ?string $status = null,
        public ?int $user_id = null,
        public ?int $vacancy_id = null,
        public ?int $per_page = 15,
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
             $request->input('status'),
            $request->input('user_id'),
             $request->input('vacancy_id'),
             $request->input('per_page', 15),
        );
    }
}
