<?php

namespace App\Events;

use App\Models\CodeSubmission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

readonly class CodeSubmissionCreated
{
    use Dispatchable, SerializesModels;
    public function __construct(private CodeSubmission $codeSubmission)
    {}
}
