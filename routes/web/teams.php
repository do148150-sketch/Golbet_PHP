<?php

use App\Http\Controllers\TeamsController;
use Illuminate\Support\Facades\Route;

Route::get('/teams' , [TeamsController::class, 'index'])->name('teams.index');
Route::get('/teams/create' , [TeamsController::class, 'create'])->name('teams.create');
Route::get('/teams/edit/{id}' , [TeamsController::class, 'edit'])->name('teams.edit');

Route::post('/teams/store' , [TeamsController::class, 'store'])->name('teams.store');
Route::put('/teams/update' , [TeamsController::class, 'update'])->name('teams.update');
Route::delete('/teams/delete/{id}' , [TeamsController::class, 'delete'])->name('teams.delete');
