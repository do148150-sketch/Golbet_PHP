<?php

use App\Http\Controllers\AccountController;
use Illuminate\Support\Facades\Route;

// Only for visitors without a session: an authenticated user is sent back home
Route::middleware('guest')->group(function() {

    Route::get('/login' , [AccountController::class, 'login'])->name('login');
    Route::post('/login' , [AccountController::class, 'loginPost'])->name('loginPost');
});

// Logging out changes state: POST, never a GET link
Route::middleware('auth')->group(function() {

    Route::post('/logout' , [AccountController::class, 'logout'])->name('logout');
});
