<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VacancyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:50|max:5000',
            'location' => 'required|string|max:255',
            'salary_from' => 'required|numeric|min:0',
            'salary_to' => 'required|numeric|min:0|gte:salary_from',
            'deadline' => 'required|date|after:today',
            'is_active' => 'required|boolean',
            'category_id' => [
                'required',
                'exists:categories,id',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->whereNull('parent_id');
                })
            ]
        ];

        // Yangi vakansiya yaratishda slug kerak emas
        if ($this->isMethod('post')) {
            unset($rules['slug']);
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'salary_to.gte' => 'The maximum salary must be greater than or equal to the minimum salary.',
            'category_id.exists' => 'The selected category is invalid or is a subcategory.',
            'deadline.after' => 'The deadline must be a date after today.'
        ];
    }

    public function prepareForValidation()
    {
        if ($this->isMethod('post')) {
            $this->merge([
                'user_id' => $this->user()->id,
                'is_active' => $this->is_active ?? true
            ]);
        }
    }
}