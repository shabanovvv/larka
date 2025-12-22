<?php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Services\CodeSubmissionService;
use Illuminate\Console\Command;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command as CommandAlias;

class CodeSubmissionForgotCommand extends Command
{
    private const int DAYS = 5;
    private const array TABLE_COLUMNS = ['ID', 'Заголовок', 'Статус', 'Студент', 'Дата создания'];

    protected $signature = 'codesubmission:forgot {days?}';

    protected $description = 'Находит не проверенные работы студентов, которым более N дней';

    public function handle(CodeSubmissionService $codeSubmissionService): int
    {
        if (!$this->argument('days')) {
            $days = $this->ask('За сколько дней искать просроченные работы?', self::DAYS);
        } else {
            $days = $this->argument('days');
        }

        try {
            $days = $this->validateDays($days);
            $submissions = $codeSubmissionService->findForgotByDays($days);
        } catch (InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return CommandAlias::FAILURE;
        } catch (\Exception $exception) {
            $this->error("Произошла ошибка: " . $exception->getMessage());

            return CommandAlias::FAILURE;
        }

        if ($submissions->isEmpty()) {
            $this->alert('Не найдено работ');

            return self::SUCCESS;
        }

        $tableData = $submissions->map(function ($submission) {
           return [
               'id' => $submission->id,
               'title' => $submission->title,
               'status' => $submission->status->label(),
               'student_id' => $submission->mentor->name ?? null,
               'created_at' => $submission->created_at,
           ];
        })->toArray();

        $this->warn("Найдено {$submissions->count()} непроверенных работ старше {$days} дней:");
        $this->table(self::TABLE_COLUMNS, $tableData);

        return self::SUCCESS;
    }

    private function validateDays(mixed $days): int
    {
        if (!is_numeric($days)) {
            throw new InvalidArgumentException('Параметр "days" должен быть числом');
        }

        $days = (int)$days;

        if ($days <= 0) {
            throw new InvalidArgumentException('Параметр "days" должен быть положительным числом');
        }

        return $days;
    }
}
