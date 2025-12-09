<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

/**
 * Пример контроллера публичного API пользователей.
 */
class UserController extends Controller
{
    public function __construct(readonly private UserService $userService)
    {
    }

    /**
     * Возвращает данные пользователя по ID.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = $this->userService->findById($id);

        return response()->json(['user' => $user]);
    }

    /**
     * @return JsonResponse
     */
    public function profile(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();
        return response()->json(
            $user->only(['id', 'name', 'email'])
        );
    }
}
