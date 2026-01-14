<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreCodeSubmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'technologyId' => ['required', 'array', 'min:1'],
            'technologyId.*' => ['integer', 'distinct', 'exists:technologies,id'],
            'code' => ['required', 'string', 'min:3'],
        ];
    }
}
