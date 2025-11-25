<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

readonly class UserService
{
    public function __construct(private UserRepository $userRepository)
    {}

    public function paginate(int $perPage): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage);
    }

    public function store(array $data): User
    {
        $data = $this->prepareData($data);

        return $this->userRepository->create($data);
    }

    public function update(User $user, array $data): User
    {
        $data = $this->prepareData($data);
        $this->userRepository->update($user, $data);

        return $user->refresh();
    }

    private function prepareData(array $data): array
    {
        // Хешируем пароль, если он передан
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            // Убираем пустой пароль из данных (при редактировании)
            unset($data['password']);
        }

        return $data;
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}

