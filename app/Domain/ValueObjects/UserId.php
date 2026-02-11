<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Идентификатор пользователя (Value Object).
 */
final readonly class UserId
{
    public function __construct(private int $value)
    {
        if ($value <= 0) {
            throw new InvalidArgumentException('UserId должен быть положительным целым числом.');
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
