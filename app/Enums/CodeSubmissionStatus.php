<?php

namespace App\Enums;

/**
 * Этапы жизненного цикла отправки кода студентом.
 */
enum CodeSubmissionStatus: string
{
    case DRAFT = 'draft';
    case WAITING = 'waiting';
    case AI_PROCESSING = 'ai_processing';
    case AI_READY = 'ai_ready';
    case AI_FAILED = 'ai_failed';
    case IN_REVIEW = 'in_review';
    case DONE = 'done';
    case REJECTED = 'rejected';

    /**
     * Текстовая метка для отображения в UI.
     *
     * @return string
     */
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::WAITING => 'Waiting for Review',
            self::AI_PROCESSING => 'AI Processing',
            self::AI_READY => 'AI Ready',
            self::AI_FAILED => 'AI Failed',
            self::IN_REVIEW => 'In Review',
            self::DONE => 'Completed',
            self::REJECTED => 'Rejected',
        };
    }

    /**
     * Возвращает список значений статусов.
     *
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
