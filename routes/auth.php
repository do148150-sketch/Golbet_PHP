<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// Only for visitors without a session: an authenticated user is sent back home
Route::middleware('guest')->group(function() {

    Route::get('/login' , [AccountController::class, 'login'])->name('login');
    Route::post('/login' , [AccountController::class, 'loginPost'])->name('loginPost');

    Route::get('/register' , [AccountController::class, 'register'])->name('register');
    Route::post('/register' , [AccountController::class, 'registerPost'])->name('registerPost');

    // Recuperar contraseña. Los nombres password.* son los que usa Laravel
    // para armar el enlace del correo de recuperacion.
    Route::get('/forgot-password' , [AccountController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password' , [AccountController::class, 'forgotPasswordPost'])->name('password.email');
    Route::get('/reset-password/{token}' , [AccountController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password' , [AccountController::class, 'resetPasswordPost'])->name('password.update');
});

// Logging out changes state: POST, never a GET link
Route::middleware('auth')->group(function() {

    Route::post('/logout' , [AccountController::class, 'logout'])->name('logout');
});
