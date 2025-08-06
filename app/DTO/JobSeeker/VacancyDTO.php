<?php
namespace App\DTO\JobSeeker;

class VacancyDTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $cover_letter
    ) {}

    public static function fromRequest($request): self
    {
        return new self(
             $request->user()->id,
             $request->input('cover_letter')
        );
    }
}
