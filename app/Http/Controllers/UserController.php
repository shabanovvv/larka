<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

/**
 * Пример контроллера публичного API пользователей.
 */
class UserController extends Controller
{
    /**
     * Возвращает данные пользователя по ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        return response()->json([
            'user' => [
                'id' => $id,
                'name' => 'Иван Иванов',
                'email' => 'user@example.com'
            ]
        ]);
    }

    /**
     * Возвращает профиль текущего авторизованного пользователя.
     *
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        return response()->json([
            'user' => [
                'id' => 1,
                'name' => 'Текущий пользователь',
                'email' => 'current@example.com'
            ]
        ]);
    }
}
