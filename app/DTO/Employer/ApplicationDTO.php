<?php

namespace App\DTO\Employer;

class ApplicationDTO
{
    public static function fromRequest(array $data): string
    {
        return $data['status'];
    }
}
