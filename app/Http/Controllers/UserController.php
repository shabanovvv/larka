<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
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
