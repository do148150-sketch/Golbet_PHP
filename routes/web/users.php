<?php

use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

Route::get('/users' , [UsersController::class, 'index'])->name('users.index');
Route::get('/users/create' , [UsersController::class, 'create'])->name('users.create');
Route::get('/users/edit/{id}' , [UsersController::class, 'edit'])->name('users.edit');

Route::post('/users/store' , [UsersController::class, 'store'])->name('users.store');
Route::put('/users/update' , [UsersController::class, 'update'])->name('users.update');
