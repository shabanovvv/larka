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
     *
     * @param int $perPage
     * @param SortDTO $sortDTO
     * @return LengthAwarePaginator<int, Role>
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        return $this->roleRepository->paginate($perPage, $sortDTO);
    }

    /**
     * Создаёт новую роль.
     *
     * @param array<string, mixed> $data
     * @return Role
     */
    public function store(array $data): Role
    {
        $data = $this->prepareData($data);

        return $this->roleRepository->create($data);
    }

    /**
     * Обновляет существующую роль.
     *
     * @param Role $role
     * @param array<string, mixed> $data
     * @return Role
     */
    public function update(Role $role, array $data): Role
    {
        $data = $this->prepareData($data);
        $this->roleRepository->update($role, $data);

        return $role->refresh();
    }

    /**
     * Точка для преобразования входных данных.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * Удаляет роль.
     *
     * @param Role $role
     * @return void
     */
    public function delete(Role $role): void
    {
        $this->roleRepository->delete($role);
    }
}
