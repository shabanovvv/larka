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
     */
    public function create(array $data): MentorProfile
    {
        return MentorProfile::query()->create($data);
    }

    /**
     * Обновляет профиль ментора.
     */
    public function update(MentorProfile $mentorProfile, array $data): bool
    {
        return $mentorProfile->update($data);
    }

    /**
     * Удаляет профиль ментора.
     */
    public function delete(MentorProfile $mentorProfile): bool
    {
        return $mentorProfile->delete();
    }
}
