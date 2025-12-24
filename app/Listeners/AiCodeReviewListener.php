<?php

namespace App\Listeners;

use App\Events\CodeSubmissionCreated;
use Illuminate\Contracts\Queue\ShouldQueue;

class AiCodeReviewListener implements ShouldQueue
{
    public function __construct()
    {}

    public function handle(CodeSubmissionCreated $event): void
    {

    }
}
