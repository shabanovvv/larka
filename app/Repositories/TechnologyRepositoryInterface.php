<?php

namespace App\Repositories;

use App\DTO\PaginateDTO;
use App\Models\Technology;
use Illuminate\Pagination\LengthAwarePaginator;

interface TechnologyRepositoryInterface
{
    public function all(): array;
    public function paginate(PaginateDTO $paginateDTO): LengthAwarePaginator;
    public function create(array $data): Technology;
    public function update(Technology $technology, array $data): bool;
    public function delete(Technology $technology): bool;
}
