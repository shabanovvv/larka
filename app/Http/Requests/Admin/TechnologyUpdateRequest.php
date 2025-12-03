<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *  Request: обновление существующей технологии.
 *  Автоматически игнорирует уникальные поля текущей модели.
 */
class TechnologyUpdateRequest extends FormRequest
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
     * Правила валидации для обновления технологии.
     *
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $technologyId = $this->route('technology')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('technologies', 'name')->ignore($technologyId)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('technologies', 'slug')->ignore($technologyId)],
        ];
    }
}
