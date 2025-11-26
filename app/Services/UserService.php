<?php

namespace App\Services;

use App\DTO\SortDTO;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

/**
 * Сервис для CRUD-операций над пользователями админки.
 */
readonly class UserService
{
    public function __construct(private UserRepository $userRepository)
    {}

    /**
     * Возвращает страницу пользователей с сортировкой.
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage, $sortDTO);
    }

    /**
     * Создаёт нового пользователя.
     */
    public function store(array $data): User
    {
        $data = $this->prepareData($data);

        return $this->userRepository->create($data);
    }

    /**
     * Обновляет пользователя и возвращает актуальную модель.
     */
    public function update(User $user, array $data): User
    {
        $data = $this->prepareData($data);
        $this->userRepository->update($user, $data);

        return $user->refresh();
    }

    /**
     * Нормализует данные перед сохранением (хеширует пароль).
     */
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

    /**
     * Удаляет пользователя.
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}



