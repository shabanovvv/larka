<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Идентификатор технологии.
 */
final readonly class TechnologyId
{
    public function __construct(
        private int $value
    ) {
        if ($value <= 0) {
            throw new InvalidArgumentException('TechnologyId должен быть положительным целым числом.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
