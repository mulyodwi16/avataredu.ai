<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ForgotPasswordController;

// Landing page
Route::get('/', function () {
    return view('pages.landing');
})->name('home');

// Auth routes (guest only)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Forgot Password
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPassword'])
        ->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
        ->name('password.email');
    Route::get('/reset-password/{token}', [ForgotPasswordController::class, 'showResetPassword'])
        ->name('password.reset');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])
        ->name('password.store');

    // ==== Google OAuth (guest) ====
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])
        ->name('google.redirect');

    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
        ->name('google.callback');
});

// Dashboard + logout (auth only)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Account Settings
    Route::get('/account', function () {
        return view('pages.account');
    })->name('account');

    // Course List
    Route::get('/courses', function () {
        return view('pages.course');
    })->name('courses');

    // Collection
    Route::get('/collection', function () {
        return view('pages.collection');
    })->name('collection');

    // Purchase History
    Route::get('/purchase-history', function () {
        return view('pages.purchase-history');
    })->name('purchase-history');

    // Institution Learning
    Route::get('/institution', function () {
        return view('pages.institution');
    })->name('institution');

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
