<?php

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
               ->uncompromised()],
        
            'password_confirmation' => 'required|string',
            'role' => ['required', Rule::in(['employer', 'user'])],
        ];
    }
    public function messages(): array
    {
        return [
            'password.uncompromised' => 'This password has appeared in a data leak. Please choose a different password.',
            'role.in' => 'Role must be either employer or user',
        ];
    }



}
