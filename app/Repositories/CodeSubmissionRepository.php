<?php

namespace App\Repositories;

use App\Enums\CodeSubmissionStatus;
use App\Models\CodeSubmission;
use Illuminate\Database\Eloquent\Collection;

class CodeSubmissionRepository
{
    public function __construct()
    {
    }

    public function getById(int $id): CodeSubmission
    {
        return CodeSubmission::query()
            ->findOrFail($id);
    }

    public function findForgotByDays(int $days): Collection
    {
        return CodeSubmission::query()
            ->with('mentor')
            ->where('created_at', '<', now()->subDays($days))
            // "Забытые" работы — те, что ожидают ревью (включая состояние после успешного AI-анализа).
            ->whereIn('status', [CodeSubmissionStatus::WAITING, CodeSubmissionStatus::AI_READY])
            ->orderBy('id', 'desc')
            ->get();
    }

    public function save(CodeSubmission $codeSubmission): CodeSubmission
    {
        $codeSubmission->save();

        return $codeSubmission;
    }
}
