<?php

namespace App\Domain\Mentoring;

use App\Domain\ValueObjects\ReviewStatus;
use App\Domain\ValueObjects\SubmissionId;
use App\Domain\ValueObjects\UserId;
use App\Enums\ReviewStatus as ReviewStatusEnum;
use App\Models\Review;
use DomainException;

/**
 * Агрегат ревью ментора (Mentoring & Workflow).
 * Защитный стиль: ревью не может быть завершено без назначенного ментора.
 */
final readonly class ReviewAggregate
{
    private function __construct(private Review $model) {}

    public static function fromModel(Review $model): self
    {
        return new self($model);
    }

    public function getSubmissionId(): SubmissionId
    {
        return new SubmissionId($this->model->code_submission_id);
    }

    public function getMentorId(): ?UserId
    {
        return $this->model->mentor_id ? new UserId($this->model->mentor_id) : null;
    }

    public function getStatus(): ReviewStatus
    {
        return new ReviewStatus($this->model->status);
    }

    public function getFinalComment(): ?string
    {
        return $this->model->comment;
    }

    /** Назначить ментора на ревью. */
    public function assignMentor(UserId $mentorId): void
    {
        $this->model->mentor_id = $mentorId->value();
        if ($this->model->status === ReviewStatusEnum::PENDING) {
            $this->model->status = ReviewStatusEnum::ASSIGNED;
        }
    }

    /**
     * Завершить ревью с финальным комментарием и рейтингом.
     *
     * @throws DomainException если ментор не назначен
     */
    public function complete(string $comment, ?int $rating = null): void
    {
        if ($this->model->mentor_id === null) {
            throw new DomainException('Ревью не может быть завершено без назначенного ментора.');
        }

        $this->model->comment = $comment;
        $this->model->rating = $rating;
        $this->model->status = ReviewStatusEnum::COMPLETED;
    }

    /** Отклонить ревью (например, плохое качество кода). */
    public function reject(?string $comment = null): void
    {
        if ($this->model->mentor_id === null) {
            throw new DomainException('Ревью не может быть отклонено без назначенного ментора.');
        }

        $this->model->comment = $comment ?? $this->model->comment;
        $this->model->status = ReviewStatusEnum::REJECTED;
    }

    public function getModel(): Review
    {
        return $this->model;
    }
}
