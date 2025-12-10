<?php

namespace Tests\Unit;

use App\DTO\SortDTO;
use App\Models\Technology;
use App\Repositories\TechnologyRepository;
use App\Services\TechnologyService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class TechnologyServiceTest extends TestCase
{
    private TechnologyRepository $repository;
    private TechnologyService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(TechnologyRepository::class);
        $this->service = new TechnologyService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_paginate(): void
    {
        $perPage = 10;
        $sortDTO = new SortDTO('name', 'asc');
        $paginator = new LengthAwarePaginator([], 0, $perPage);

        $this->repository->shouldReceive('paginate')
            ->once()
            ->with($perPage, $sortDTO)
            ->andReturn($paginator);

        $result = $this->service->paginate($perPage, $sortDTO);

        $this->assertSame($paginator, $result);
    }

    #[DataProvider('storeDataProvider')]
    public function test_store(array $input, array $expected): void
    {
        $technology = new Technology($expected);

        $this->repository->shouldReceive('create')
            ->once()
            ->with($expected)
            ->andReturn($technology);

        $result = $this->service->store($input);

        $this->assertSame($expected['slug'], $result->slug);
    }

    #[DataProvider('updateDataProvider')]
    public function test_update(array $input, array $expected): void
    {
        $technology = Mockery::mock(Technology::class)->makePartial();
        $updated = Mockery::mock(Technology::class)->makePartial();
        $updated->slug = $expected['slug'];

        $this->repository->shouldReceive('update')
            ->once()
            ->with($technology, $expected)
            ->andReturnTrue();

        $technology->shouldReceive('refresh')
            ->once()
            ->andReturn($updated);

        $result = $this->service->update($technology, $input);

        $this->assertSame($expected['slug'], $result->slug);
    }

    public function test_delete(): void
    {
        $technology = Mockery::mock(Technology::class)->makePartial();

        $this->repository->shouldReceive('delete')
            ->once()
            ->with($technology)
            ->andReturnTrue();

        $this->expectNotToPerformAssertions();
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

