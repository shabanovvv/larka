<?php

namespace App\DTO;

class CodeSubmissionCreateDTO
{
    public function __construct(
        /** @var array<int, int> */
        public array $technologyIds,
        public string $code
    )
    {}

    public static function fromRequest(array $validatedData): self
    {
        return new self(
            array_map('intval', $validatedData['technologyId']),
            (string) $validatedData['code']
        );
    }
}
