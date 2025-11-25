<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleStoreRequest;
use App\Http\Requests\Admin\RoleUpdateRequest;
use App\Models\Role;
use App\Services\RoleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * CRUD-контроллер админки для управления ролями.
 */
class RoleController extends Controller
{
    /**
     * @param RoleService $roleService Сервис работы с пользователями.
     */
    public function __construct(private readonly RoleService $roleService)
    {}

    /**
     * Список пользователей с пагинацией.
     *
     * @return View
     */
    public function index(): View
    {
        return view('admin.roles.index', [
            'roles' => $this->roleService->paginate(20),
        ]);
    }

    /**
     * Форма создания пользователя.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.roles.create');
    }

    /**
     * Сохранение нового пользователя.
     *
     * @param RoleStoreRequest $request
     * @return RedirectResponse
     */
    public function store(RoleStoreRequest $request): RedirectResponse
    {
        $this->roleService->store($request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Роль успешно создана.');
    }

    /**
     * Форма редактирования пользователя.
     *
     * @param Role $role
     * @return View
     */
    public function edit(Role $role): View
    {
        return view('admin.roles.edit', [
            'role' => $role,
        ]);
    }

    /**
     * Обновление существующего пользователя.
     *
     * @param RoleUpdateRequest $request
     * @param Role $role
     * @return RedirectResponse
     */
    public function update(RoleUpdateRequest $request, Role $role): RedirectResponse
    {
        $this->roleService->update($role, $request->validated());

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Роль успешно обновлёна.');
    }

    /**
     * Удаление пользователя.
     *
     * @param Role $role
     * @return RedirectResponse
     */
    public function destroy(Role $role): RedirectResponse
    {
        $this->roleService->delete($role);

        return redirect()
            ->route('admin.roles.index')
            ->with('success', 'Роль успешно удалёна.');
    }
}
