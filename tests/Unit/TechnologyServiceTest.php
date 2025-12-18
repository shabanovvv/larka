<?php

namespace Tests\Unit;

use App\DTO\SortDTO;
use App\DTO\PaginateDTO;
use App\Models\Technology;
use App\Repositories\EloquentTechnologyRepository;
use App\Services\TechnologyService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\TestCase;

class TechnologyServiceTest extends TestCase
{
    /** @var MockObject&EloquentTechnologyRepository */
    private $repository;
    private TechnologyService $service;

    protected function setUp(): void
    {
        parent::setUp();

        // Для юнит-тестов используем in-memory кэш, чтобы не зависеть от драйвера/сериализации.
        config(['cache.default' => 'array']);
        Cache::flush();

        $this->repository = $this->createMock(EloquentTechnologyRepository::class);
        $this->service = new TechnologyService($this->repository);
    }

    public function test_paginate(): void
    {
        $paginateDTO = new PaginateDTO(
            1,
            10,
            new SortDTO('name', 'asc'),
        );
        $paginator = new LengthAwarePaginator([], 0, $paginateDTO->perPage);

        $this->repository
            ->method('validateAndGetSorting')
            ->willReturn(['name', 'desc']);

        $this->repository
            ->expects($this->once())
            ->method('paginate')
            ->with($paginateDTO)
            ->willReturn($paginator);

        $result = $this->service->paginate($paginateDTO);

        $this->assertSame($paginator, $result);
    }

    #[DataProvider('storeDataProvider')]
    public function test_store(array $input, array $expected): void
    {
        $technology = new Technology($expected);

        $this->repository
            ->expects($this->once())
            ->method('create')
            ->with($expected)
            ->willReturn($technology);

        $result = $this->service->store($input);

        $this->assertSame($expected['slug'], $result->slug);
    }

    #[DataProvider('updateDataProvider')]
    public function test_update(array $input, array $expected): void
    {
        $technology = $this->createPartialMock(Technology::class, ['refresh']);
        $updated = new Technology($expected);

        $this->repository
            ->expects($this->once())
            ->method('update')
            ->with($technology, $expected)
            ->willReturn(true);

        $technology
            ->expects($this->once())
            ->method('refresh')
            ->willReturn($updated);

        $result = $this->service->update($technology, $input);

        $this->assertSame($expected['slug'], $result->slug);
    }

    public function test_delete(): void
    {
        $technology = $this->createMock(Technology::class);

        $this->repository
            ->expects($this->once())
            ->method('delete')
            ->with($technology)
            ->willReturn(true);

        $this->service->delete($technology);
    }

    public static function storeDataProvider(): array
    {
        return [
            'slug generated' => [
                ['name' => 'Laravel Echo', 'slug' => null],
                ['name' => 'Laravel Echo', 'slug' => 'laravel-echo'],
            ],
            'slug provided' => [
                ['name' => 'PHPUnit', 'slug' => 'custom-slug'],
                ['name' => 'PHPUnit', 'slug' => 'custom-slug'],
            ],
        ];
    }

    public static function updateDataProvider(): array
    {
        return [
            'generate on update' => [
                ['name' => 'Vue Js', 'slug' => null],
                ['name' => 'Vue Js', 'slug' => 'vue-js'],
            ],
            'keep provided slug' => [
                ['name' => 'React', 'slug' => 'react-js'],
                ['name' => 'React', 'slug' => 'react-js'],
            ],
        ];
    }
}

