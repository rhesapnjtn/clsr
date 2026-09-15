<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/up', fn () => response()->json(['ok' => true]));

// ── Admin login (pure Blade) ───────────────────────────────
Route::get('/admin', fn () => view('admin-login'))->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'loginForm'])->name('admin.login.post');
Route::post('/admin/logout', [AuthController::class, 'logoutForm'])->name('admin.logout.post');

// ── Admin SPA (requires auth) ──────────────────────────────
Route::get('/admin/dashboard', fn () => view('admin-app'))->middleware('auth')->name('admin.dashboard');

// ── Public registration (catch-all Vue SPA) ────────────────
Route::get('/{any}', fn () => view('app'))
    ->where('any', '^(?!api|build|storage|up|admin).*');

// ── API ────────────────────────────────────────────────────
Route::prefix('api')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/batches/available', [BatchController::class, 'available']);
    Route::post('/registrations', [RegistrationController::class, 'store']);
    Route::post('/check-ktp', [RegistrationController::class, 'checkKtp']);

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::get('/admin/batches', [BatchController::class, 'all']);
        Route::post('/admin/batches', [BatchController::class, 'store']);
        Route::patch('/admin/batches/{batch}/toggle-lock', [BatchController::class, 'toggleLock']);
        Route::patch('/admin/batches/{batch}/reset', [BatchController::class, 'resetLock']);
        Route::patch('/admin/batches/{batch}/complete', [BatchController::class, 'complete']);

        Route::get('/admin/stats', [RegistrationController::class, 'stats']);
        Route::get('/admin/registrations', [RegistrationController::class, 'index']);
        Route::get('/admin/registrations/export', [RegistrationController::class, 'export']);
        Route::post('/admin/registrations/{registration}/invite', [RegistrationController::class, 'invite']);
        Route::post('/admin/registrations/{registration}/confirm', [RegistrationController::class, 'confirm']);
        Route::post('/admin/registrations/{registration}/attend', [RegistrationController::class, 'attend']);
        Route::post('/admin/registrations/{registration}/no-show', [RegistrationController::class, 'noShow']);
        Route::delete('/admin/registrations/{registration}', [RegistrationController::class, 'cancel']);
    });
});
