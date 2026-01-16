<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LockController;
use App\Http\Middleware\EnsurePinIsEntered;

Route::view('/', 'welcome');

Route::middleware(['auth', EnsurePinIsEntered::class])->group(function () {
    Route::view('dashboard', 'dashboard')
        ->middleware(['verified'])
        ->name('dashboard');

    Route::view('profile', 'profile')
        ->name('profile');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/lock', [LockController::class, 'index'])->name('lock.index');
    Route::post('/lock', [LockController::class, 'unlock'])->name('lock.unlock');
});

require __DIR__ . '/auth.php';
