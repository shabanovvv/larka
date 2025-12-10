<?php

namespace Tests\Unit;

use App\DTO\SortDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\UserService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use Mockery;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    private UserRepository $userRepository;
    private UserService $userService;

    public function setUp(): void
    {
        parent::setUp();

        $this->userRepository = Mockery::mock(UserRepository::class);
        $this->userService = new UserService($this->userRepository);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /**
     * A basic test example.
     */
    public function test_paginate(): void
    {
        $perPage = 20;
        $sortDTO = new SortDTO('id', 'asc');
        $paginator = new LengthAwarePaginator([], 0, $perPage);

        $this->userRepository->shouldReceive('paginate')
            ->once()
            ->with($perPage, $sortDTO)
            ->andReturn($paginator);
        $result = $this->userService->paginate($perPage, $sortDTO);

        $this->assertSame($paginator, $result);
    }

    #[DataProvider('storeDataProvider')]
    public function test_store(array $payload): void
    {
        $user = new User([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password_hash'],
        ]);

        Hash::shouldReceive('make')
            ->once()
            ->with($payload['password'])
            ->andReturn($payload['password_hash']);

        $this->userRepository->shouldReceive('create')
            ->once()
            ->with([
                'name' => $payload['name'],
                'email' => $payload['email'],
                'password' => $payload['password_hash'],
            ])
            ->andReturn($user);

        $result = $this->userService->store([
            'name' => $payload['name'],
            'email' => $payload['email'],
            'password' => $payload['password'],
        ]);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals($payload['name'], $result->name);
    }

    #[DataProvider('storeDataProvider')]
    public function test_update(array $payload): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $user->name = $payload['name'];
        $user->email = $payload['email'];
        $userUpdated = Mockery::mock(User::class)->makePartial();
        $userUpdated->name = $payload['name_new'];
        $userUpdated->email = $payload['email'];

        $this->userRepository->shouldReceive('update')
            ->once()
            ->with($user, ['name' => $payload['name_new']])
            ->andReturnTrue();

        $user->shouldReceive('refresh')->once()->andReturn($userUpdated);

        $result = $this->userService->update($user, ['name' => $payload['name_new']]);

        $this->assertEquals($payload['name_new'], $result->name);
    }

    #[DataProvider('storeDataProvider')]
    public function test_delete(array $payload): void
    {
        $user = Mockery::mock(User::class)->makePartial();
        $this->userRepository->shouldReceive('delete')
            ->once()
            ->with($user)
            ->andReturnTrue();

        $this->expectNotToPerformAssertions();
        $this->userService->delete($user);
    }

    public static function storeDataProvider(): array
    {
        return [
            'user' => [[
                'name' => 'John Doe',
                'name_new' => 'John Doe NEW',
                'email' => 'jon@example.ru',
                'password' => 'Password*10',
                'password_hash' => 'Password*10Hashaed',
            ]]
        ];
    }
}
