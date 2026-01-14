<?php

namespace App\DTO;

use App\Enums\AiProvider;

class AiAnalysisCreateDTO
{
    public function __construct(
        public int $codeSubmissionId,
        public AiProvider $provider,
        public string $summary,
        public array $suggestions,
        public int $score,
        public string $rawResponse
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['code_submission_id'],
            AiProvider::from($data['provider']),
            $data['summary'],
            $data['suggestions'],
            $data['score'],
            $data['raw_response']
        );
    }
}
