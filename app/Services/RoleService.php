<?php

namespace App\Services;

use App\DTO\SortDTO;
use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Сервис для управления ролями в админке.
 */
readonly class RoleService
{
    public function __construct(private RoleRepository $roleRepository)
    {}

    /**
     * Возвращает список ролей с сортировкой и пагинацией.
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        return $this->roleRepository->paginate($perPage, $sortDTO);
    }

    /**
     * Создаёт новую роль.
     */
    public function store(array $data): Role
    {
        $data = $this->prepareData($data);

        return $this->roleRepository->create($data);
    }

    /**
     * Обновляет существующую роль.
     */
    public function update(Role $role, array $data): Role
    {
        $data = $this->prepareData($data);
        $this->roleRepository->update($role, $data);

        return $role->refresh();
    }

    /**
     * Точка для преобразования входных данных.
     */
    private function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * Удаляет роль.
     */
    public function delete(Role $role): void
    {
        $this->roleRepository->delete($role);
    }
}
