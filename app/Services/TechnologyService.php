<?php

namespace App\Services;

use App\DTO\SortDTO;
use App\Models\Technology;
use App\Repositories\TechnologyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

/**
 * Сервис управления технологиями (CRUD + подготовка slug).
 */
readonly class TechnologyService
{
    public function __construct(private TechnologyRepository $technologyRepository)
    {}

    /**
     * Возвращает список технологий с сортировкой.
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        return $this->technologyRepository->paginate($perPage, $sortDTO);
    }

    /**
     * Создаёт новую технологию.
     */
    public function store(array $data): Technology
    {
        $data = $this->prepareData($data);

        return $this->technologyRepository->create($data);
    }

    /**
     * Обновляет технологию и возвращает свежую модель.
     */
    public function update(Technology $technology, array $data): Technology
    {
        $data = $this->prepareData($data);
        $this->technologyRepository->update($technology, $data);

        return $technology->refresh();
    }

    /**
     * Дополняет данные slug'ом, если его не передали.
     */
    private function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }

    /**
     * Удаляет технологию.
     */
    public function delete(Technology $technology): void
    {
        $this->technologyRepository->delete($technology);
    }
}
