<?php

namespace App\Enums;

enum CodeSubmissionStatus: string
{
    case DRAFT = 'draft';
    case WAITING = 'waiting';
    case IN_REVIEW = 'in_review';
    case DONE = 'done';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::WAITING => 'Waiting for Review',
            self::IN_REVIEW => 'In Review',
            self::DONE => 'Completed',
            self::REJECTED => 'Rejected',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
