<?php

namespace App\DTO;

use Illuminate\Http\Request;

/**
 * DTO для параметров пагинации и сортировки.
 */
class PaginateDTO
{
    public function __construct(
        /** Номер страницы (начиная с 1). */
        public int $page = 1,
        /** Количество элементов на странице. */
        public int $perPage = 20,
        /** Параметры сортировки (поле + направление). */
        public SortDTO $sortDTO,
    )
    {}

    /**
     * Собирает DTO из HTTP-запроса.
     * Ожидает параметры: page, perPage, sort, direction.
     */
    public static function fromRequest(Request $request): self
    {
        return new self(
            max(1, (int) $request->get('page', 1)),
            max(1, (int) $request->get('perPage', 20)),
            SortDTO::fromRequest($request),
        );
    }
}


