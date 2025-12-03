<?php

namespace App\Repositories;

use App\DTO\SortDTO;
use App\Models\MentorProfile;
use App\Traits\HasSorting;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Репозиторий профилей менторов.
 */
class MentorProfileRepository
{
    use HasSorting;

    /**
     * Разрешённые поля сортировки.
     */
    const ALLOWED_SORTS = [
        'id',
        'user_id',
        'experience_years',
        'rate',
        'is_active',
        'created_at',
    ];

    /**
     * Возвращает срез профилей с сортировкой.
     *
     * @param int $perPage
     * @param SortDTO $sortDTO
     * @return LengthAwarePaginator<int, MentorProfile>
     */
    public function paginate(int $perPage, SortDTO $sortDTO): LengthAwarePaginator
    {
        [$sort, $direction] = $this->validateAndGetSorting($sortDTO);

        return MentorProfile::query()
            ->with(['user', 'technologies'])
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Создаёт профиль ментора.
     *
     * @param array<string, mixed> $data
     * @return MentorProfile
     */
    public function create(array $data): MentorProfile
    {
        return MentorProfile::query()->create($data);
    }

    /**
     * Обновляет профиль ментора.
     *
     * @param MentorProfile $mentorProfile
     * @param array<string, mixed> $data
     * @return bool
     */
    public function update(MentorProfile $mentorProfile, array $data): bool
    {
        return $mentorProfile->update($data);
    }

    /**
     * Удаляет профиль ментора.
     *
     * @param MentorProfile $mentorProfile
     * @return bool
     */
    public function delete(MentorProfile $mentorProfile): bool
    {
        return $mentorProfile->delete();
    }
}
