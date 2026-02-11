<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Исходный код отправки (Value Object).
 * Может быть пустым (draft) или содержать код.
 */
final readonly class SourceCode
{
    private const MAX_LENGTH = 1_000_000;

    public function __construct(
        private string $value
    ) {
        if (strlen($value) > self::MAX_LENGTH) {
            throw new InvalidArgumentException(
                'Исходный код не может превышать ' . self::MAX_LENGTH . ' байт.'
            );
        }
    }

    public static function empty(): self
    {
        return new self('');
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isEmpty(): bool
    {
        return $this->value === '';
    }
}
