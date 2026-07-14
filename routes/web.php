<?php

use App\Http\Controllers\Auth\OAuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/', [OAuthController::class, 'login'])->name('login');
    Route::get('/auth/github/redirect', [OAuthController::class, 'redirect'])->name('auth.github.redirect');
    Route::get('/auth/github/callback', [OAuthController::class, 'callback'])->name('auth.github.callback');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/logout', [OAuthController::class, 'logout'])->name('logout');
});
