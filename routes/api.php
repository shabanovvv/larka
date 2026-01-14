<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\AiAnalysisController;
use App\Http\Controllers\Api\CodeSubmissionController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TechnologyController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('web')->group(function () {
    Route::post('register', RegisterController::class);
    Route::post('login', LoginController::class);
    Route::post('logout', LogoutController::class)->middleware('auth');
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [UserController::class, 'profile']);
    Route::get('/technologies', [TechnologyController::class, 'list']);

    Route::post('/code-submission', [CodeSubmissionController::class, 'store']);
    Route::get('/code-submission/{id}/status', [AiAnalysisController::class, 'showLatest']);
});

// Публичные маршруты
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::get('/user/{id}', [UserController::class, 'show']);
