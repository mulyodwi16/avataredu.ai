<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\CreatorDashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'creator'])->prefix('creator')->name('creator.')->group(function () {
    // Creator Dashboard
    Route::get('/dashboard', [CreatorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [CreatorDashboardController::class, 'stats'])->name('stats');

    // Course Management
    Route::resource('courses', CourseController::class);
});