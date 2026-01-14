<?php

namespace App\Http\Controllers\Api;

use App\DTO\CodeSubmissionCreateDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StoreCodeSubmissionRequest;
use App\Services\CodeSubmissionService;
use Illuminate\Http\JsonResponse;

class CodeSubmissionController extends Controller
{
    public function __construct(private readonly CodeSubmissionService $codeSubmissionService)
    {}

    public function store(StoreCodeSubmissionRequest $request): JsonResponse
    {
        $user = $request->user();
        if (!$user) {
            return new JsonResponse(['message' => 'Unauthenticated.'], 401);
        }

        $dto = CodeSubmissionCreateDTO::fromRequest($request->validated());
        $codeSubmission = $this->codeSubmissionService->store($user, $dto);

        return new JsonResponse([
            'id' => $codeSubmission->id,
            'message' => 'Отправлено',
        ], 201);
    }
}
