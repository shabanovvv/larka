<?php

namespace App\DTO;


use Illuminate\Http\Request;

/**
 * DTO, хранящий выбранную колонку сортировки и направление.
 */
class SortDTO
{
    /**
     * @param string|null $sort      Имя поля, по которому нужно сортировать.
     * @param string|null $direction Направление сортировки: asc|desc.
     */
    public function __construct(
        public ?string $sort,
        public ?string $direction,
    )
    {}

    /**
     * Создаёт DTO из входящего HTTP-запроса.
     *
     * @param Request $request
     * @return SortDTO
     */
    public static function fromRequest(Request $request): SortDTO
    {
        return new self(
            $request->get('sort'),
            $request->get('direction')
        );
    }
}
