<?php

namespace App\Enums;

enum ReviewStatus: string
{
    case PENDING = 'pending';        // Ожидает назначения ментора
    case ASSIGNED = 'assigned';      // Назначен ментор, но еще не начал
    case IN_PROGRESS = 'in_progress'; // Ментор начал ревью
    case NEEDS_CLARIFICATION = 'needs_clarification'; // Нужны уточнения от студента
    case REVISED = 'revised';        // Студент внес правки, ожидает повторного ревью
    case COMPLETED = 'completed';    // Ревью завершено успешно
    case REJECTED = 'rejected';      // Ревью отклонено (плохое качество кода)
    case CANCELLED = 'cancelled';    // Ревью отменено

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Assignment',
            self::ASSIGNED => 'Assigned to Mentor',
            self::IN_PROGRESS => 'Review in Progress',
            self::NEEDS_CLARIFICATION => 'Needs Clarification',
            self::REVISED => 'Waiting for Re-review',
            self::COMPLETED => 'Completed',
            self::REJECTED => 'Rejected',
            self::CANCELLED => 'Cancelled',
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
