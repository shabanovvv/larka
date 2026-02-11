<?php

namespace App\Domain\ValueObjects;

use InvalidArgumentException;

/**
 * Электронная почта пользователя (Value Object).
 */
final readonly class Email
{
    public function __construct(private string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new InvalidArgumentException('Email не может быть пустым.');
        }
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Недопустимый формат email: {$value}");
        }
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return strcasecmp($this->value, $other->value) === 0;
    }
}
