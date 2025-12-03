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
     *
     * @param int $perPage
     * @param SortDTO $sortDTO
     * @return LengthAwarePaginator<int, MentorProfile>
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        return $this->mentorProfileRepository->paginate($perPage, $sortDTO);
    }

    /**
     * Создаёт новый профиль ментора.
     *
     * @param array<string, mixed> $data
     * @return MentorProfile
     */
    public function store(array $data): MentorProfile
    {
        $data = $this->prepareData($data);

        return $this->mentorProfileRepository->create($data);
    }

    /**
     * Обновляет профиль ментора и возвращает актуальную модель.
     *
     * @param MentorProfile $mentorProfile
     * @param array<string, mixed> $data
     * @return MentorProfile
     */
    public function update(MentorProfile $mentorProfile, array $data): MentorProfile
    {
        $data = $this->prepareData($data);
        $this->mentorProfileRepository->update($mentorProfile, $data);

        return $mentorProfile->refresh();
    }

    /**
     * Точка для нормализации входящих данных.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function prepareData(array $data): array
    {
        return $data;
    }

    /**
     * Удаляет профиль ментора.
     *
     * @param MentorProfile $mentorProfile
     * @return void
     */
    public function delete(MentorProfile $mentorProfile): void
    {
        $this->mentorProfileRepository->delete($mentorProfile);
    }
}
