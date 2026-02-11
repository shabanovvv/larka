<?php

namespace App\Domain\CodeReview;

use App\Domain\CodeReview\Entity\AiAnalysisEntity;
use App\Domain\ValueObjects\SourceCode;
use App\Domain\ValueObjects\SubmissionId;
use App\Domain\ValueObjects\SubmissionStatus;
use App\Domain\ValueObjects\TechnologyId;
use App\Domain\ValueObjects\UserId;
use App\Enums\AiProvider;
use App\Enums\CodeSubmissionStatus;
use App\Models\AiAnalysis;
use App\Models\CodeSubmission;
use DomainException;
use Illuminate\Support\Collection;

/**
 * Агрегат отправки кода на ревью (Code Review).
 * Защитный стиль: состояние меняется только через бизнес-методы.
 * AiAnalysis — сущность внутри агрегата, не существует без CodeSubmission.
 */
final class CodeSubmissionAggregate
{
    /** @var list<AiAnalysisEntity> Новые анализы, ещё не сохранённые в БД */
    private array $pendingAiAnalyses = [];

    private function __construct(
        private CodeSubmission $model,
        /** @var Collection<int, AiAnalysisEntity> */
        private Collection $aiAnalysisEntities
    ) {}

    public static function fromModel(CodeSubmission $model): self
    {
        $entities = $model->aiAnalyses->map(fn (AiAnalysis $a) => new AiAnalysisEntity(
            $a->id,
            $a->provider,
            (string) $a->summary,
            is_array($a->suggestions) ? $a->suggestions : [],
            (int) $a->score,
            is_string($a->raw_response) ? $a->raw_response : json_encode($a->raw_response ?? [])
        ));

        return new self($model, $entities);
    }

    public function getSubmissionId(): SubmissionId
    {
        return new SubmissionId($this->model->id);
    }

    public function getStudentId(): UserId
    {
        return new UserId($this->model->user_id);
    }

    public function getStatus(): SubmissionStatus
    {
        return new SubmissionStatus($this->model->status);
    }

    public function getSourceCode(): SourceCode
    {
        return new SourceCode((string) $this->model->code);
    }

    /** @return list<TechnologyId> */
    public function getTechnologyIds(): array
    {
        $ids = $this->model->relationLoaded('technologies')
            ? $this->model->technologies->pluck('id')->map(fn ($id) => new TechnologyId((int) $id))
            : $this->model->technologies()->pluck('id')->map(fn ($id) => new TechnologyId((int) $id));

        return $ids->values()->all();
    }

    /**
     * Добавить результат AI-анализа. Допустимо только в статусе AI_PROCESSING.
     *
     * @param array<int, string> $suggestions
     * @throws DomainException
     */
    public function addAiAnalysis(
        AiProvider $provider,
        string $summary,
        array $suggestions,
        int $score,
        string $rawResponse
    ): void {
        $status = $this->getStatus();
        if (!$status->canAddAiAnalysis()) {
            throw new DomainException(
                'Добавление AI-анализа возможно только в статусе AI_PROCESSING. Текущий статус: ' . $status->value()->value
            );
        }

        $this->pendingAiAnalyses[] = new AiAnalysisEntity(
            null,
            $provider,
            $summary,
            $suggestions,
            $score,
            $rawResponse
        );
    }

    /** Перевести отправку в статус «AI-анализ успешно выполнен». */
    public function markAiReady(): void
    {
        $this->model->status = CodeSubmissionStatus::AI_READY;
    }

    /** Перевести отправку в статус «AI-анализ завершился с ошибкой». */
    public function markAiFailed(): void
    {
        $this->model->status = CodeSubmissionStatus::AI_FAILED;
    }

    /** Модель для персистенции (репозиторий сохраняет её и связанные сущности). */
    public function getModel(): CodeSubmission
    {
        return $this->model;
    }

    /** @return list<AiAnalysisEntity> */
    public function getPendingAiAnalyses(): array
    {
        return $this->pendingAiAnalyses;
    }

    /** После сохранения вызывать для очистки pending. */
    public function clearPendingAiAnalyses(): void
    {
        $this->pendingAiAnalyses = [];
    }
}
