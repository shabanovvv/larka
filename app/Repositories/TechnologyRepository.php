<?php

namespace App\Repositories;

use App\DTO\SortDTO;
use App\Models\Technology;
use App\Traits\HasSorting;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Репозиторий доступа к технологиям.
 */
class TechnologyRepository
{
    use HasSorting;

    /**
     * Допустимые поля сортировки.
     */
    const ALLOWED_SORTS = [
        'id',
        'name',
        'slug',
    ];

    /**
     * Возвращает страницу технологий с сортировкой.
     *
     * @param int $perPage
     * @param SortDTO $sortDTO
     * @return LengthAwarePaginator<int, Technology>
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        [$sort, $direction] = $this->validateAndGetSorting($sortDTO);

        return Technology::query()
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Создаёт технологию.
     *
     * @param array<string, mixed> $data
     * @return Technology
     */
    public function create(array $data): Technology
    {
        return Technology::query()->create($data);
    }

    /**
     * Обновляет технологию.
     *
     * @param Technology $technology
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(Technology $technology, array $data): bool
    {
        return $technology->update($data);
    }

    /**
     * Удаляет технологию.
     *
     * @param Technology $technology
     * @return bool
     */
    public function delete(Technology $technology): bool
    {
        return $technology->delete();
    }
}
