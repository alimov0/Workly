<?php
 namespace App\DTO\Admin;

 use Illuminate\Http\Request;
 
 class UserCreateDTO
 {
     public string $name;
     public string $email;
     public string $password;
     public ?string $role;
 
     public function __construct(array $data)
     {
         $this->name = $data['name'];
         $this->email = $data['email'];
         $this->password = $data['password'];
         $this->role = $data['role'] ?? null;
     }
 
     public static function fromRequest(Request $request): self
     {
         return new self([
             'name' => $request->input('name'),
             'email' => $request->input('email'),
             'password' => $request->input('password'),
             'role' => $request->input('role'),
         ]);
     }
 }
 