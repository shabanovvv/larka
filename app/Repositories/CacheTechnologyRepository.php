<?php

namespace App\Repositories;

use App\DTO\PaginateDTO;
use App\Models\Technology;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use LogicException;

readonly class CacheTechnologyRepository implements TechnologyRepositoryInterface
{
    private const CACHE_TTL_SECONDS = 60;
    private const CACHE_TAG_PAGINATE = 'technologies_paginate';

    public function __construct(private TechnologyRepositoryInterface $technologyRepository)
    {
    }

    public function paginate(PaginateDTO $paginateDTO): LengthAwarePaginator
    {
        $cacheKey = $this->paginateCacheKey($paginateDTO);

        return Cache::tags([self::CACHE_TAG_PAGINATE])->remember(
            $cacheKey,
            self::CACHE_TTL_SECONDS,
            fn() => $this->technologyRepository->paginate($paginateDTO)
        );
    }

    public function create(array $data): Technology
    {
        $technology = $this->technologyRepository->create($data);
        $this->clearPaginateCache();

        return $technology;
    }

    public function update(Technology $technology, array $data): bool
    {
        $updated = $this->technologyRepository->update($technology, $data);
        if ($updated) {
            $this->clearPaginateCache();
        }

        return $updated;
    }

    public function delete(Technology $technology): bool
    {
        $deleted = $this->technologyRepository->delete($technology);
        if ($deleted) {
            $this->clearPaginateCache();
        }

        return $deleted;
    }


    /**
     * Ключ кэша для списка технологий должен учитывать параметры пагинации и сортировки.
     */
    private function paginateCacheKey(PaginateDTO $paginateDTO): string
    {
        if (!($this->technologyRepository instanceof EloquentTechnologyRepository)) {
            throw new LogicException(sprintf(
                'CacheTechnologyRepository expects %s, got %s',
                EloquentTechnologyRepository::class,
                get_debug_type($this->technologyRepository),
            ));
        }

        // Нормализуем сортировку в кэш-репозитории (не полагаемся на методы декорируемого репозитория).
        [$sort, $direction] = $this->technologyRepository->validateAndGetSorting($paginateDTO->sortDTO);

        return sprintf(
            'technologies:paginate:page:%d:perPage:%d:sort:%s:dir:%s',
            $paginateDTO->page,
            $paginateDTO->perPage,
            $sort,
            $direction
        );
    }

    /**
     * Очищает кэш пагинации технологий.
     */
    public function clearPaginateCache(): void
    {
        Cache::tags([self::CACHE_TAG_PAGINATE])->flush();
    }

}
