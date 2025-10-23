<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    // Account Settings
    Route::get('/account', function () {
        return view('pages.account');
    })->name('account');

    // Course detail API for dashboard
    Route::get('/api/courses/{course}', [App\Http\Controllers\DashboardController::class, 'getCourseDetail'])->name('api.courses.detail');

    // Dashboard content APIs
    Route::get('/api/dashboard/collection', [App\Http\Controllers\DashboardController::class, 'getMyCollection'])->name('api.dashboard.collection');
    Route::get('/api/dashboard/purchase-history', [App\Http\Controllers\DashboardController::class, 'getPurchaseHistory'])->name('api.dashboard.purchase-history');

    // Enrollment routes (only keep processing - UI handled via dashboard)
    Route::post('/courses/{course}/enroll', [App\Http\Controllers\EnrollmentController::class, 'processEnrollment'])->name('courses.process-enrollment');

    // Course Learning
    Route::get('/courses/{course}/learn', [App\Http\Controllers\CourseController::class, 'learn'])->name('courses.learn');

    // SCORM Package Files (protected by enrollment check)
    Route::get('/courses/{course}/scorm/{path?}', [App\Http\Controllers\ScormController::class, 'serveScormFile'])->name('courses.scorm-file')->where('path', '.*');

    // Purchase/Transaction management
    Route::get('/transactions/{transaction}', [App\Http\Controllers\PurchaseController::class, 'show'])->name('transactions.show');
    Route::get('/transactions/{transaction}/invoice', [App\Http\Controllers\PurchaseController::class, 'downloadInvoice'])->name('transactions.invoice');
    Route::post('/transactions/{transaction}/retry', [App\Http\Controllers\PurchaseController::class, 'retryPayment'])->name('transactions.retry');

    // Lesson Progress API
    Route::post('/api/lessons/{lesson}/complete', [App\Http\Controllers\LessonProgressController::class, 'markComplete'])->name('lessons.complete');
    Route::post('/api/lessons/{lesson}/incomplete', [App\Http\Controllers\LessonProgressController::class, 'markIncomplete'])->name('lessons.incomplete');
    Route::post('/api/lessons/{lesson}/progress', [App\Http\Controllers\LessonProgressController::class, 'updateProgress'])->name('lessons.progress');

    // Course completion routes
    Route::post('/api/courses/{course}/complete', [App\Http\Controllers\EnrollmentController::class, 'markCourseComplete'])->name('courses.complete');
    Route::post('/api/courses/{course}/incomplete', [App\Http\Controllers\EnrollmentController::class, 'markCourseIncomplete'])->name('courses.incomplete');

    // Institution Learning
    Route::get('/institution', [App\Http\Controllers\InstitutionController::class, 'index'])->name('institution');
    Route::get('/institutions/{institution}', [App\Http\Controllers\InstitutionController::class, 'show'])->name('institutions.show');
    Route::get('/institutions/search', [App\Http\Controllers\InstitutionController::class, 'search'])->name('institutions.search');

    // Dashboard API for single page navigation
    Route::get('/api/dashboard/{page}', [App\Http\Controllers\DashboardApiController::class, 'getContent']);

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin routes (admin only)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Check admin middleware
    Route::middleware('admin')->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        // Dashboard sub-routes (better organization)
        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('/createcourse', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'showChooseCourseType'])->name('createcourse');
            Route::get('/courses/create', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'showCreateCourse'])->name('courses.create-regular');
            Route::get('/courses/create/scorm', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'showCreateScormCourse'])->name('courses.create-scorm-form');
            Route::get('/editcourse/{course}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'showEditCourse'])->name('editcourse');
        });

        // Admin Dashboard API for single page navigation  
        Route::get('/api/dashboard/{page}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'getContent'])->where('page', '.*')->name('api.dashboard');
        Route::delete('/api/courses/{course}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'deleteCourse'])->name('courses.api.delete');

        // User Management API (Super Admin only)
        Route::get('/api/users/{user}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'getUser'])->name('users.api.get');
        Route::post('/api/users', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'createUser'])->name('users.api.create');
        Route::put('/api/users/{user}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'updateUser'])->name('users.api.update');
        Route::delete('/api/users/{user}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'deleteUser'])->name('users.api.delete');
        Route::post('/api/users/{user}/make-admin', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'makeUserAdmin'])->name('users.api.make-admin');

        // Course Management API handled via AdminDashboardApiController
        Route::get('/courses', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'index'])->name('courses.index');

        // Course API Routes
        Route::post('/courses', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'storeCourse'])->name('courses.store');
        Route::get('/courses/{course}/edit', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'edit'])->name('courses.edit');
        Route::put('/courses/{course}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'updateCourse'])->name('courses.update');
        Route::delete('/courses/{course}', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'deleteCourse'])->name('courses.delete');
        Route::post('/courses/{course}/reorder-chapters', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'reorderChapters'])->name('courses.reorder-chapters');

        // Simple Video Upload
        Route::post('/courses/{course}/upload-video', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'uploadVideo'])->name('courses.upload-video');
        Route::delete('/courses/{course}/delete-video', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'deleteVideo'])->name('courses.delete-video');

        // SCORM Course Creation
        Route::post('/courses/create-scorm', [App\Http\Controllers\Admin\AdminDashboardApiController::class, 'createScormCourse'])->name('courses.create-scorm');

        // Course Content Management (Chapters & Lessons)
        Route::prefix('courses/{course}')->name('courses.')->group(function () {
            Route::get('/content', [App\Http\Controllers\Admin\CourseContentController::class, 'index'])->name('content.index');

            // Chapter routes
            Route::post('/chapters', [App\Http\Controllers\Admin\CourseContentController::class, 'storeChapter'])->name('chapters.store');
            Route::put('/chapters/{chapter}', [App\Http\Controllers\Admin\CourseContentController::class, 'updateChapter'])->name('chapters.update');
            Route::delete('/chapters/{chapter}', [App\Http\Controllers\Admin\CourseContentController::class, 'deleteChapter'])->name('chapters.delete');

            // Lesson routes
            Route::post('/chapters/{chapter}/lessons', [App\Http\Controllers\Admin\CourseContentController::class, 'storeLesson'])->name('lessons.store');
            Route::put('/lessons/{lesson}', [App\Http\Controllers\Admin\CourseContentController::class, 'updateLesson'])->name('lessons.update');
            Route::delete('/lessons/{lesson}', [App\Http\Controllers\Admin\CourseContentController::class, 'deleteLesson'])->name('lessons.delete');
        });

        // User Management
        Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('users.show');
        Route::put('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('users.update');

        // API Routes for Chapters
        Route::get('/api/courses/{courseId}/chapters', function (Request $request, $courseId) {
            try {
                $chapters = \App\Models\CourseChapter::where('course_id', $courseId)
                    ->orderBy('order')
                    ->get()
                    ->map(function ($chapter) {
                        return [
                            'id' => $chapter->id,
                            'title' => $chapter->title
                        ];
                    });

                return response()->json([
                    'success' => true,
                    'chapters' => $chapters
                ]);
            } catch (\Exception $e) {
                \Log::error('Get course chapters error: ' . $e->getMessage());
                return response()->json([
                    'success' => false,
                    'error' => 'Error loading chapters'
                ], 500);
            }
        })->name('api.courses.chapters');

        // Pages Management
        Route::get('/pages', [App\Http\Controllers\Admin\AdminPagesController::class, 'index'])->name('pages.index');
        Route::post('/pages', [App\Http\Controllers\Admin\AdminPagesController::class, 'store'])->name('pages.store');
        Route::get('/pages/create', [App\Http\Controllers\Admin\AdminPagesController::class, 'create'])->name('pages.create');
        Route::get('/pages/{page}/edit', [App\Http\Controllers\Admin\AdminPagesController::class, 'edit'])->name('pages.edit');
        Route::put('/pages/{page}', [App\Http\Controllers\Admin\AdminPagesController::class, 'update'])->name('pages.update');
        Route::delete('/pages/{page}', [App\Http\Controllers\Admin\AdminPagesController::class, 'destroy'])->name('pages.destroy');

        // Category Management
        Route::get('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('categories.destroy');

        // SCORM Manager
        Route::get('/scorm-manager', [App\Http\Controllers\Admin\ScormManagerController::class, 'index'])->name('scorm.index');
        Route::post('/scorm-manager/upload', [App\Http\Controllers\Admin\ScormManagerController::class, 'upload'])->name('scorm.upload');
        Route::get('/scorm/{course}/view', [App\Http\Controllers\Admin\ScormManagerController::class, 'viewPackage'])->name('scorm.view-package');
    });
});



