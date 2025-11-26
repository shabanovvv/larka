<?php

namespace App\Services;

use App\DTO\SortDTO;
use App\Models\MentorProfile;
use App\Repositories\MentorProfileRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Бизнес-логика работы с профилями менторов.
 */
readonly class MentorProfileService
{
    public function __construct(private MentorProfileRepository $mentorProfileRepository)
    {}
    /**
     * Возвращает страницу профилей с учётом сортировки.
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        return $this->mentorProfileRepository->paginate($perPage, $sortDTO);
    }

    /**
     * Создаёт новый профиль ментора.
     */
    public function store(array $data): MentorProfile
    {
        $data = $this->prepareData($data);

        return $this->mentorProfileRepository->create($data);
    }

    /**
     * Обновляет профиль ментора и возвращает актуальную модель.
     */
    public function update(MentorProfile $mentorProfile, array $data): MentorProfile
    {
        $data = $this->prepareData($data);
        $this->mentorProfileRepository->update($mentorProfile, $data);

        return $mentorProfile->refresh();
    }

    /**
     * Точка для нормализации входящих данных.
     */
    private function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * Удаляет профиль ментора.
     */
    public function delete(MentorProfile $mentorProfile): void
    {
        $this->mentorProfileRepository->delete($mentorProfile);
    }
}
