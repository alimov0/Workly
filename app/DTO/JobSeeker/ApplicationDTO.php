<?php 
namespace App\DTO\JobSeeker;

class ApplicationDTO
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $cover_letter,
        public readonly string $resume_path
    ) {}

    public static function fromRequest($request, $resumePath): self
    {
        return new self(
             $request->user()->id,
             $request->cover_letter,
             $resumePath
        );
    }
}
