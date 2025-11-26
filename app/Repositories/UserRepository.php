<?php

namespace App\Repositories;

use App\DTO\SortDTO;
use App\Models\User;
use App\Traits\HasSorting;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Репозиторий для CRUD-операций над пользователями.
 */
class UserRepository
{
    use HasSorting;

    /**
     * Перечень полей, по которым можно сортировать.
     */
    const ALLOWED_SORTS = [
        'id',
        'name',
        'email',
        'created_at',
    ];
    /**
     * Возвращает пользователей с пагинацией и сортировкой.
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        [$sort, $direction] = $this->validateAndGetSorting($sortDTO);

        return User::query()
            ->with('roles')
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Создаёт пользователя.
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    /**
     * Обновляет пользователя.
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Удаляет пользователя.
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}



