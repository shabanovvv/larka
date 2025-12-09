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
     *
     * @param int $perPage
     * @param SortDTO $sortDTO
     * @return LengthAwarePaginator<int, User>
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        return $this->userRepository->paginate($perPage, $sortDTO);
    }

    /**
     * @param int $id
     * @return User
     */
    public function findById(int $id): User
    {
        $this->userRepository->findById($id);
    }

    /**
     * Создаёт нового пользователя.
     *
     * @param array<string, mixed> $data
     * @return User
     */
    public function store(array $data): User
    {
        $data = $this->prepareData($data);

        return $this->userRepository->create($data);
    }

    /**
     * Обновляет пользователя и возвращает актуальную модель.
     *
     * @param User $user
     * @param array<string, mixed> $data
     * @return User
     */
    public function update(User $user, array $data): User
    {
        $data = $this->prepareData($data);
        $this->userRepository->update($user, $data);

        return $user->refresh();
    }

    /**
     * Нормализует данные перед сохранением (хеширует пароль).
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
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
     *
     * @param User $user
     * @return void
     */
    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }
}



