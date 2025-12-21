<?php

namespace App\Services;

use App\Repositories\CodeSubmissionRepository;
use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;

readonly class CodeSubmissionService
{
    public function __construct(private CodeSubmissionRepository $codeSubmissionRepository)
    {
    }

    public function findForgotByDays(int $days): Collection
    {
        if ($days <= 0) {
            throw new InvalidArgumentException('Дни могут быть только положительным числом');
        }

        return $this->codeSubmissionRepository->findForgotByDays($days);
    }
}
