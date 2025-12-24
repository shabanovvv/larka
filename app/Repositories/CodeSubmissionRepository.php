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

    public function findForgotByDays(int $days): Collection
    {
        return CodeSubmission::query()
            ->with('mentor')
            ->where('created_at', '<', now()->subDays($days))
            ->where('status', CodeSubmissionStatus::WAITING)
            ->orderBy('id', 'desc')
            ->get();
    }
}
