<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ResumeAnalyzeRequest extends FormRequest
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
            'resume_text' => ['nullable', 'string', 'max:10000'],
            'resume' => ['nullable', 'file', 'mimes:pdf,txt', 'max:2048'],
            'job_id' => ['nullable', 'exists:jobs,id'],
        ];
    }
}
