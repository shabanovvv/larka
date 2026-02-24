<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Auth\TokenController;
use App\Http\Controllers\Api\AiAnalysisController;
use App\Http\Controllers\Api\CodeSubmissionController;
use App\Http\Controllers\Api\TechnologyController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

// Выдача токена без web (сессия/CSRF не нужны, только Bearer для API)
Route::post('auth/token', TokenController::class);

Route::prefix('auth')->middleware('web')->group(function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
    Route::post('logout', LogoutController::class)->middleware('auth');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::apiResource('technologies', TechnologyController::class);

    Route::post('/code-submission', [CodeSubmissionController::class, 'store']);
    Route::get('/code-submission/{id}/status', [AiAnalysisController::class, 'showLatest']);
});

// Публичные маршруты
Route::get('/user/{id}', [UserController::class, 'show']);
