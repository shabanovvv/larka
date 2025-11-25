<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request: создание новой технологии.
 * Валидирует входящие данные для метода store().
 */
class TechnologyStoreRequest extends FormRequest
{
    /**
     * Разрешает выполнение запроса.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Правила валидации для создания технологии.
     *
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('technologies', 'name')],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('technologies', 'slug')],
        ];
    }
}
