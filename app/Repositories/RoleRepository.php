<?php

namespace App\Repositories;

use App\DTO\SortDTO;
use App\Models\Role;
use App\Traits\HasSorting;
use Illuminate\Pagination\LengthAwarePaginator;
use RuntimeException;

/**
 * Репозиторий для доступа к ролям.
 */
class RoleRepository
{
    use HasSorting;

    /**
     * Перечень разрешённых полей для сортировки.
     */
    const ALLOWED_SORTS = [
        'id',
        'name',
    ];

    /**
     * Возвращает роли с пагинацией и счётчиком пользователей.
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        [$sort, $direction] = $this->validateAndGetSorting($sortDTO);

        return Role::query()
            ->withCount('users')
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Создаёт роль.
     */
    public function create(array $data): Role
    {
        return Role::query()->create($data);
    }

    /**
     * Обновляет роль.
     */
    public function update(Role $role, array $data): bool
    {
        return $role->update($data);
    }

    /**
     * Удаляет роль, оборачивая исключения в RuntimeException.
     */
    public function delete(Role $role): bool
    {
        try {
            return $role->delete();
        } catch (\Exception $exception) {
            throw new RuntimeException('Ошибка удаления Роли: ' . $exception->getMessage());
        }
    }
}

