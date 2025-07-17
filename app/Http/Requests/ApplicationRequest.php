<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
{
   
    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        return [
            'cover_letter' => 'required|string|min:50|max:1000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB
        ];
    }
      public function messages()
    {
        return [
            'resume.max' => 'The resume file must not be greater than 5MB.',
            'cover_letter.min' => 'The cover letter must be at least 50 characters.'
        ];
    }


}
