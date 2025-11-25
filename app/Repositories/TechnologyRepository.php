<?php

namespace App\Repositories;

use App\Models\Technology;
use Illuminate\Pagination\LengthAwarePaginator;

class TechnologyRepository
{
    public function paginate(int $perPage): LengthAwarePaginator
    {
        return Technology::query()->paginate($perPage)->withQueryString();
    }

    public function create(array $data): Technology
    {
        return Technology::query()->create($data);
    }

    public function update(Technology $technology, array $data): bool
    {
        return $technology->update($data);
    }

    public function delete(Technology $technology): bool
    {
        return $technology->delete();
    }
}
