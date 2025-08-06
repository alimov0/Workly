<?php 
namespace App\DTO\Auth;

use App\Http\Requests\Auth\RegisterRequest;

class RegisterDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role,
    ) {}

    public static function fromRequest(RegisterRequest $request)
    {
        return new self(
            $request->input('name'),
            email: $request->input('email'),
            password: $request->input('password'),
                role:$request->input('role'),
        );
    }
}
