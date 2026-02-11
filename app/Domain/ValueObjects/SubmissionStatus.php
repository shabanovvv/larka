<?php

namespace App\Domain\ValueObjects;

use App\Enums\CodeSubmissionStatus;
use InvalidArgumentException;

/**
 * Статус отправки кода (Value Object, обёртка над enum).
 */
final readonly class SubmissionStatus
{
    public function __construct(
        private CodeSubmissionStatus $value
    ) {}

    public static function fromString(string $value): self
    {
        return new self(CodeSubmissionStatus::from($value));
    }

    public function value(): CodeSubmissionStatus
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function canAddAiAnalysis(): bool
    {
        return $this->value === CodeSubmissionStatus::AI_PROCESSING;
    }

    public function isTerminal(): bool
    {
        return in_array($this->value, [
            CodeSubmissionStatus::AI_READY,
            CodeSubmissionStatus::AI_FAILED,
            CodeSubmissionStatus::DONE,
            CodeSubmissionStatus::REJECTED,
        ], true);
    }
}
