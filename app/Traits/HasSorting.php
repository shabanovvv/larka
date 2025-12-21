<?php

namespace App\Traits;

use App\DTO\SortDTO;

/**
 * Трейт с утилитой для безопасной сортировки в репозиториях/сервисах.
 * Ожидает, что класс определит константу ALLOWED_SORTS со списком колонок.
 */
trait HasSorting
{
    /**
     * Валидирует колонку и направление сортировки и возвращает итоговую пару.
     *
     * @param SortDTO $sortDTO
     * @return array{0: string, 1: string} [поле, направление]
     */
    public function validateAndGetSorting(SortDTO $sortDTO): array
    {
        $allowedSorts = self::ALLOWED_SORTS;

        $sort = in_array($sortDTO->sort, $allowedSorts)
            ? $sortDTO->sort
            : $allowedSorts[0] ?? 'id';

        $direction = $sortDTO->direction === 'asc' ? 'asc' : 'desc';

        return [$sort, $direction];
    }
}
