<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserController;

// Публичные маршруты
Route::get('/events', [EventController::class, 'index']);
Route::get('/events/{id}', [EventController::class, 'show']);
Route::get('/user/{id}', [UserController::class, 'show']);

Route::get('/profile', [UserController::class, 'profile']);
