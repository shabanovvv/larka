<?php

namespace Tests\Unit;

use App\DTO\SortDTO;
use App\Models\MentorProfile;
use App\Repositories\MentorProfileRepository;
use App\Services\MentorProfileService;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Tests\TestCase;

class MentorProfileServiceTest extends TestCase
{
    private MentorProfileRepository $repository;
    private MentorProfileService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = Mockery::mock(MentorProfileRepository::class);
        $this->service = new MentorProfileService($this->repository);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_paginate(): void
    {
        $perPage = 25;
        $sortDTO = new SortDTO('rate', 'desc');
        $paginator = new LengthAwarePaginator([], 0, $perPage);

        $this->repository->shouldReceive('paginate')
            ->once()
            ->with($perPage, $sortDTO)
            ->andReturn($paginator);

        $this->assertSame($paginator, $this->service->paginate($perPage, $sortDTO));
    }

    public function test_store(): void
    {
        $payload = ['user_id' => 10, 'rate' => 5000];
        $profile = new MentorProfile($payload);

        $this->repository->shouldReceive('create')
            ->once()
            ->with($payload)
            ->andReturn($profile);

        $result = $this->service->store($payload);

        $this->assertInstanceOf(MentorProfile::class, $result);
    }

    public function test_update(): void
    {
        $profile = Mockery::mock(MentorProfile::class)->makePartial();
        $updated = Mockery::mock(MentorProfile::class)->makePartial();
        $updated->rate = 6000;

        $this->repository->shouldReceive('update')
            ->once()
            ->with($profile, ['rate' => 6000])
            ->andReturnTrue();

        $profile->shouldReceive('refresh')
            ->once()
            ->andReturn($updated);

        $result = $this->service->update($profile, ['rate' => 6000]);

        $this->assertSame(6000, $result->rate);
    }

    public function test_delete(): void
    {
        $profile = Mockery::mock(MentorProfile::class)->makePartial();

        $this->repository->shouldReceive('delete')
            ->once()
            ->with($profile)
            ->andReturnTrue();

        $this->expectNotToPerformAssertions();
        $this->service->delete($profile);
    }
}

