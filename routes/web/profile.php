<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Cada usuario ve y edita solo lo suyo: basta con estar autenticado,
// no hace falta un permiso del AuthorizedMiddleware.
Route::middleware('auth')->group(function() {

    Route::get('/profile' , [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile' , [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/profile/password' , [ProfileController::class, 'password'])->name('profile.password');
    Route::put('/profile/password' , [ProfileController::class, 'updatePassword'])->name('profile.updatePassword');
});
