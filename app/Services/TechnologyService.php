<?php

namespace App\Services;

use App\Models\Technology;
use App\Repositories\TechnologyRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

readonly class TechnologyService
{
    public function __construct(private TechnologyRepository $technologyRepository)
    {}

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return $this->technologyRepository->paginate($perPage);
    }

    public function store(array $data): Technology
    {
        $data = $this->prepareData($data);

        return $this->technologyRepository->create($data);
    }

    public function update(Technology $technology, array $data): Technology
    {
        $data = $this->prepareData($data);
        $this->technologyRepository->update($technology, $data);

        return $technology->refresh();
    }

    private function prepareData(array $data): array
    {
        if (empty($data['slug']) && !empty($data['name'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        return $data;
    }

    public function delete(Technology $technology): void
    {
        $this->technologyRepository->delete($technology);
    }
}
