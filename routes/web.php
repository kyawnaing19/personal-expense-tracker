<?php

use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;

// Login page
// Route::get('/', function () {
//     return view('auth.login');
// })->name('login');

// // Google Auth
// Route::get('auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
// Route::get('auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// // Protected routes
// Route::middleware('auth')->group(function () {
//     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
//     Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
// });
