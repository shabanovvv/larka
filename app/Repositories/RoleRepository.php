<?php

namespace App\Repositories;

use App\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleRepository
{
    public function paginate(int $perPage): LengthAwarePaginator
    {
        return Role::query()->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Role
    {
        return Role::query()->create($data);
    }

    public function update(Role $user, array $data): bool
    {
        return $user->update($data);
    }

    public function delete(Role $user): bool
    {
        return $user->delete();
    }
}

