<?php

namespace App\Domain\CodeReview\Entity;

use App\Enums\AiProvider;

/**
 * Сущность AI-анализа внутри агрегата CodeSubmission.
 * Не существует без отправки кода.
 */
final class AiAnalysisEntity
{
    public function __construct(
        private ?int $id,
        private AiProvider $provider,
        private string $summary,
        /** @var array<int, string> */
        private array $suggestions,
        private int $score,
        private string $rawResponse
    ) {
        if ($score < 0 || $score > 100) {
            throw new \InvalidArgumentException('Score должен быть от 0 до 100.');
        }
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function provider(): AiProvider
    {
        return $this->provider;
    }

    public function summary(): string
    {
        return $this->summary;
    }

    /** @return array<int, string> */
    public function suggestions(): array
    {
        return $this->suggestions;
    }

    public function score(): int
    {
        return $this->score;
    }

    public function rawResponse(): string
    {
        return $this->rawResponse;
    }
}
