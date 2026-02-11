<?php

namespace App\Domain\ValueObjects;

use App\Enums\ReviewStatus as ReviewStatusEnum;
use InvalidArgumentException;

/**
 * Статус ревью ментора (Value Object).
 */
final readonly class ReviewStatus
{
    public function __construct(
        private ReviewStatusEnum $value
    ) {}

    public static function fromString(string $value): self
    {
        return new self(ReviewStatusEnum::from($value));
    }

    public function value(): ReviewStatusEnum
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function isCompleted(): bool
    {
        return in_array($this->value, [
            ReviewStatusEnum::COMPLETED,
            ReviewStatusEnum::REJECTED,
            ReviewStatusEnum::CANCELLED,
        ], true);
    }
}
