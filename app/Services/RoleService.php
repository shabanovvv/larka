<?php

namespace App\Services;

use App\Models\Role;
use App\Repositories\RoleRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

readonly class RoleService
{
    public function __construct(private RoleRepository $roleRepository)
    {}

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return $this->roleRepository->paginate($perPage);
    }

    public function store(array $data): Role
    {
        $data = $this->prepareData($data);

        return $this->roleRepository->create($data);
    }

    public function update(Role $role, array $data): Role
    {
        $data = $this->prepareData($data);
        $this->roleRepository->update($role, $data);

        return $role->refresh();
    }

    private function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }

    public function delete(Role $role): void
    {
        $this->roleRepository->delete($role);
    }
}
