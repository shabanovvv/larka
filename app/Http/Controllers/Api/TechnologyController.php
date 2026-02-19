<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TechnologyStoreRequest;
use App\Http\Requests\Admin\TechnologyUpdateRequest;
use App\Models\Technology;
use App\Services\TechnologyService;
use Illuminate\Http\JsonResponse;

/**
 * CRUD API для сущности Technology.
 */
class TechnologyController extends Controller
{
    public function __construct(private readonly TechnologyService $technologyService)
    {}

    /**
     * Список технологий (list).
     */
    public function index(): JsonResponse
    {
        $items = $this->technologyService->all();

        return response()->json(['data' => $items]);
    }

    /**
     * Одна технология по id (show).
     */
    public function show(Technology $technology): JsonResponse
    {
        return response()->json(['data' => $technology]);
    }

    /**
     * Создание технологии (store).
     */
    public function store(TechnologyStoreRequest $request): JsonResponse
    {
        $technology = $this->technologyService->store($request->validated());

        return response()->json(['data' => $technology], 201);
    }

    /**
     * Обновление технологии (update).
     */
    public function update(TechnologyUpdateRequest $request, Technology $technology): JsonResponse
    {
        $technology = $this->technologyService->update($technology, $request->validated());

        return response()->json(['data' => $technology]);
    }

    /**
     * Удаление технологии (destroy).
     */
    public function destroy(Technology $technology): JsonResponse
    {
        $this->technologyService->delete($technology);

        return response()->json(null, 204);
    }
}
