<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'events' => [
                [
                    'id' => 1,
                    'title' => 'Концерт рок-группы',
                    'date' => '2024-12-25',
                    'location' => 'Москва, Крокус Сити Холл'
                ],
                [
                    'id' => 2,
                    'title' => 'Выставка современного искусства',
                    'date' => '2024-12-20',
                    'location' => 'Санкт-Петербург, Эрмитаж'
                ]
            ]
        ]);
    }

    public function show(int $id): JsonResponse
    {
        return response()->json([
            'event' => [
                'id' => $id,
                'title' => 'Детали мероприятия ' . $id,
                'description' => 'Описание мероприятия...'
            ]
        ]);
    }
}
