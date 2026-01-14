<?php

namespace App\Services;

use App\DTO\CodeSubmissionCreateDTO;
use App\Enums\CodeSubmissionStatus;
use App\Events\CodeSubmissionCreated;
use App\Models\CodeSubmission;
use App\Models\User;
use App\Repositories\CodeSubmissionRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

/**
 * Сервис для работы с отправками кода на ревью:
 * создание submission, привязка технологий и генерация доменных событий.
 */
readonly class CodeSubmissionService
{
    public function __construct(private CodeSubmissionRepository $codeSubmissionRepository)
    {
    }

    public function getById(int $id): CodeSubmission
    {
        return $this->codeSubmissionRepository->getById($id);
    }

    /**
     * Создаёт отправку кода на ревью для переданного пользователя.
     * Сохраняет модель, записывает связи в pivot и диспатчит событие после commit.
     */
    public function store(User $user, CodeSubmissionCreateDTO $dto): CodeSubmission
    {
        $codeSubmission = new CodeSubmission();
        $codeSubmission->code = $dto->code;
        $codeSubmission->user()->associate($user);
        // Один статус: после отправки сразу идём в AI обработку.
        $codeSubmission->status = CodeSubmissionStatus::AI_PROCESSING;

        DB::transaction(function () use ($codeSubmission, $dto) {
            $this->codeSubmissionRepository->save($codeSubmission);
            $codeSubmission->technologies()->sync($dto->technologyIds);
            DB::afterCommit(function () use ($codeSubmission) {
                event(new CodeSubmissionCreated($codeSubmission));
            });
        });

        return $codeSubmission;
    }

    public function setStatus(CodeSubmission $codeSubmission, CodeSubmissionStatus $status): CodeSubmission
    {
        $codeSubmission->status = $status;

        return $this->codeSubmissionRepository->save($codeSubmission);
    }

    /**
     * Возвращает список "забытых" отправок (в статусе WAITING), созданных раньше N дней назад.
     *
     * @return Collection<int, CodeSubmission>
     * @throws InvalidArgumentException если $days <= 0
     */
    public function findForgotByDays(int $days): Collection
    {
        if ($days <= 0) {
            throw new InvalidArgumentException('Дни могут быть только положительным числом');
        }

        return $this->codeSubmissionRepository->findForgotByDays($days);
    }
}
