<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request для создания нового профиля ментора.
 */
class MentorProfileStoreRequest extends FormRequest
{
    /**
     * Проверяет, может ли пользователь выполнить запрос.
     */
    public function authorize(): bool
    {
        return true; // или твоя логика прав
    }

    /**
     * Набор правил валидации для сохранения профиля.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'description' => ['nullable', 'string'],
            'experience_years' => ['nullable', 'integer', 'min:0'],
            'rate' => ['nullable', 'numeric', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Подготавливает входящие данные (checkbox → boolean).
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
