<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request для обновления существующего профиля ментора.
 */
class MentorProfileUpdateRequest extends FormRequest
{
    /**
     * Проверяет права пользователя на выполнение запроса.
     */
    public function authorize(): bool
    {
        return true; // или права доступа
    }

    /**
     * Правила валидации при обновлении профиля.
     *
     * @return array<string, array<int, mixed>>
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
     * Нормализует checkbox-поле в булево значение.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
