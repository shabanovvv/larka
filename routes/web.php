<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MentorProfileController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TechnologyController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::resource('/technologies', TechnologyController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/mentor-profile', MentorProfileController::class);
});
