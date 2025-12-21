<?php

namespace App\Services;

use App\DTO\PaginateDTO;
use App\Models\Technology;
use App\Repositories\TechnologyRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

/**
 * Сервис управления технологиями (CRUD + подготовка slug).
 */
readonly class TechnologyService
{
    public function __construct(private TechnologyRepositoryInterface $technologyRepository)
    {}

    /**
     * Возвращает список технологий с сортировкой.
     *
     * @param PaginateDTO $paginateDTO
     * @return LengthAwarePaginator<int, Technology>
     */
    public function paginate(PaginateDTO $paginateDTO): LengthAwarePaginator
    {
        return $this->technologyRepository->paginate($paginateDTO);
    }

    /**
     * Создаёт новую технологию.
     *
     * @param array<string, mixed> $data
     * @return Technology
     */
    public function store(array $data): Technology
    {
        $data = $this->prepareData($data);

        return $this->technologyRepository->create($data);
    }

    /**
     * Обновляет технологию и возвращает свежую модель.
     *
     * @param Technology $technology
     * @param array<string, mixed> $data
     * @return Technology
     */
    public function update(Technology $technology, array $data): Technology
    {
        $data = $this->prepareData($data);
        $this->technologyRepository->update($technology, $data);

        return $technology->refresh();
    }

    /**
     * Дополняет данные slug'ом, если его не передали.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
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
     *
     * @param Technology $technology
     * @return void
     */
    public function delete(Technology $technology): void
    {
        $this->technologyRepository->delete($technology);
    }
}
