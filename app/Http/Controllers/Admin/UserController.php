<?php

namespace App\Http\Controllers\Admin;

use App\DTO\SortDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

/**
 * CRUD-контроллер админки для управления пользователями.
 */
class UserController extends Controller
{
    /**
     * @param UserService $userService Сервис работы с пользователями.
     */
    public function __construct(private readonly UserService $userService)
    {}

    /**
     * Список пользователей с пагинацией.
     *
     * @param Request $request
     * @return View
     */
    public function index(Request $request): View
    {
        $sortDTO = SortDTO::fromRequest($request);

        return view('admin.users.index', [
            'users' => $this->userService->paginate(20, $sortDTO),
        ]);
    }

    /**
     * Форма создания пользователя.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Сохранение нового пользователя.
     *
     * @param UserStoreRequest $request
     * @return RedirectResponse
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $this->userService->store($request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пользователь успешно создан.');
    }

    /**
     * Форма редактирования пользователя.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'user' => $user,
        ]);
    }

    /**
     * Обновление существующего пользователя.
     *
     * @param UserUpdateRequest $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        $this->userService->update($user, $request->validated());

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пользователь успешно обновлён.');
    }

    /**
     * Удаление пользователя.
     *
     * @param User $user
     * @return RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->userService->delete($user);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Пользователь успешно удалён.');
    }
}
