<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SeoController;
use App\Http\Controllers\SettingsController;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\EnsureUserIsApproved;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => auth()->check()
    ? redirect()->route('dashboard')
    : view('landing'));

// SEO & Search Indexing Routes
Route::get('/robots.txt', [SeoController::class, 'robots']);
Route::get('/sitemap.xml', [SeoController::class, 'sitemap']);
Route::get('/extension', fn () => view('extension'))->name('extension');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated user (pending approval allowed for logout & pending screen)
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/pending', [AuthController::class, 'showPending'])->name('pending');
    Route::post('/feedback', [FeedbackController::class, 'store'])->name('feedback.store');
});

// Authenticated & Approved app
Route::middleware(['auth', EnsureUserIsApproved::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/page/{page}', [DashboardController::class, 'index'])->name('dashboard.page');
    Route::get('/applied', [DashboardController::class, 'applied'])->name('jobs.applied');
    Route::get('/applied/page/{page}', [DashboardController::class, 'applied'])->name('jobs.applied.page');
    Route::get('/jobs/{id}', [DashboardController::class, 'show'])->name('jobs.show');
    Route::post('/jobs/{id}/generate', [DashboardController::class, 'generate'])->name('jobs.generate');
    Route::post('/jobs/{id}/toggle-applied', [DashboardController::class, 'toggleApplied'])->name('jobs.toggleApplied');
    Route::post('/tour/complete', [DashboardController::class, 'completeTour'])->name('tour.complete');

    // Settings
    Route::get('/settings', [SettingsController::class, 'edit'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'update'])->name('settings.update');
    Route::post('/settings/test-telegram', [SettingsController::class, 'testTelegram'])->name('settings.testTelegram');
    Route::get('/settings/verification', [SettingsController::class, 'verification'])->name('settings.verification');

    Route::post('/notifications/seen', [NotificationController::class, 'markSeen'])->name('notifications.seen');
});

// Admin panel (requires admin role)
Route::middleware(['auth', EnsureUserIsApproved::class, EnsureUserIsAdmin::class])->group(function () {
    Route::get('/admin/users', [AdminController::class, 'index'])->name('admin.users');
    Route::post('/admin/users/{user}/toggle-approval', [AdminController::class, 'toggleApproval'])->name('admin.users.toggle-approval');
    Route::post('/admin/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('admin.users.toggle-admin');
    Route::post('/admin/users/{user}/update', [AdminController::class, 'updateUser'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser'])->name('admin.users.delete');

    // Feedback admin portal
    Route::get('/admin/feedback', [AdminController::class, 'feedback'])->name('admin.feedback');
    Route::delete('/admin/feedback/{feedback}', [AdminController::class, 'deleteFeedback'])->name('admin.feedback.delete');
});
