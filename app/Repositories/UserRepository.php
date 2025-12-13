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
     *
     * @param int $perPage
     * @param SortDTO $sortDTO
     * @return LengthAwarePaginator<int, User>
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
     * @param int $id
     * @return User
     */
    public function findById(int $id): User
    {
        return User::query()->findOrFail($id);
    }

    /**
     * Создаёт пользователя.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function create(array $data): User
    {
        return User::query()->create($data);
    }

    /**
     * Обновляет пользователя.
     *
     * @param User $user
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    /**
     * Удаляет пользователя.
     *
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        return $user->delete();
    }
}



