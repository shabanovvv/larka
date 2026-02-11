<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Название технологии (Value Object).
 */
final readonly class TechnologyName
{
    private const MAX_LENGTH = 255;

    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException('Название технологии не может быть пустым.');
        }
        if (mb_strlen($value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                'Название технологии не может быть длиннее ' . self::MAX_LENGTH . ' символов.'
            );
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
