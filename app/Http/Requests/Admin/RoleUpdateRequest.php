<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *  Request: обновление существующей роли.
 *  Автоматически игнорирует уникальные поля текущей модели.
 */
class RoleUpdateRequest extends FormRequest
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
     * Правила валидации для обновления роли.
     *
     * @return array[]
     */
    public function rules(): array
    {
        $roleId = $this->route('role')?->id;

        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('roles', 'name')->ignore($roleId)],
        ];
    }
}
