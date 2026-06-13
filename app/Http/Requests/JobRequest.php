<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'category_id' => ['required', 'exists:categories,id'],
            'title' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string', 'min:30'],
            'requirements' => ['required', 'string', 'min:10'],
            'salary_range' => ['nullable', 'string', 'max:80'],
            'location' => ['required', 'string', 'max:120'],
            'employment_type' => ['required', 'in:Full-time,Part-time,Contract,Internship,Remote'],
            'deadline' => ['required', 'date', 'after:today'],
        ];
    }
}
