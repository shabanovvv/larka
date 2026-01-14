<?php

namespace App\Repositories;

use App\DTO\PaginateDTO;
use App\Models\Technology;
use App\Traits\HasSorting;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * Репозиторий доступа к технологиям.
 */
class EloquentTechnologyRepository implements TechnologyRepositoryInterface
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
     * Возвращает все значения
     */
    public function all(): array
    {
        return Technology::all()->toArray();
    }

    /**
     * Возвращает страницу технологий с сортировкой.
     *
     * @param PaginateDTO $paginateDTO
     * @return LengthAwarePaginator<int, Technology>
     */
    public function paginate(PaginateDTO $paginateDTO): LengthAwarePaginator
    {
        [$sort, $direction] = $this->validateAndGetSorting($paginateDTO->sortDTO);

        return Technology::query()
            ->orderBy($sort, $direction)
            ->paginate($paginateDTO->perPage, ['*'], 'page', $paginateDTO->page);
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
