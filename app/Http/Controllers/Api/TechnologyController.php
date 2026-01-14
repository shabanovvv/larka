<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\TechnologyService;

class TechnologyController extends Controller
{
    public function __construct(private readonly TechnologyService $technologyService)
    {}

    public function list(): array
    {
        return $this->technologyService->all();
    }
}
