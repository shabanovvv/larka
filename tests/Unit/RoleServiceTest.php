<?php

namespace Tests\Unit;

use App\DTO\SortDTO;
use App\Models\Role;
use App\Repositories\RoleRepository;
use App\Services\RoleService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class RoleServiceTest extends TestCase
{
    private RoleRepository $repository;
    private RoleService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(RoleRepository::class);
        $this->service = new RoleService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_paginate(): void
    {
        $perPage = 15;
        $sortDTO = new SortDTO('id', 'desc');
        $paginator = new LengthAwarePaginator([], 0, $perPage);

        $this->repository->shouldReceive('paginate')
            ->once()
            ->with($perPage, $sortDTO)
            ->andReturn($paginator);

        $this->assertSame($paginator, $this->service->paginate($perPage, $sortDTO));
    }

    public function test_store(): void
    {
        $payload = ['name' => 'Admin'];
        $role = new Role($payload);

        $this->repository->shouldReceive('create')
            ->once()
            ->with($payload)
            ->andReturn($role);

        $result = $this->service->store($payload);

        $this->assertInstanceOf(Role::class, $result);
        $this->assertSame('Admin', $result->name);
    }

    public function test_update(): void
    {
        $role = Mockery::mock(Role::class)->makePartial();
        $updated = Mockery::mock(Role::class)->makePartial();
        $updated->name = 'Editor';

        $this->repository->shouldReceive('update')
            ->once()
            ->with($role, ['name' => 'Editor'])
            ->andReturnTrue();

        $role->shouldReceive('refresh')
            ->once()
            ->andReturn($updated);

        $result = $this->service->update($role, ['name' => 'Editor']);

        $this->assertSame('Editor', $result->name);
    }

    public function test_delete(): void
    {
        $role = Mockery::mock(Role::class)->makePartial();

        $this->repository->shouldReceive('delete')
            ->once()
            ->with($role)
            ->andReturnTrue();

        $this->expectNotToPerformAssertions();
        $this->service->delete($role);
    }
}

