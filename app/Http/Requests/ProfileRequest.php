<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'headline' => ['nullable', 'string', 'max:140'],
            'location' => ['nullable', 'string', 'max:120'],
            'summary' => ['nullable', 'string', 'max:1500'],
            'skills' => ['nullable', 'string', 'max:500'],
            'education' => ['nullable', 'string', 'max:1000'],
            'experience' => ['nullable', 'string', 'max:1000'],
            'profile_picture' => ['nullable', 'image', 'max:2048'],
            'resume' => ['nullable', 'file', 'mimes:pdf', 'max:2048'],
        ];
    }
}
