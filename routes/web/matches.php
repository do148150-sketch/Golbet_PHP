<?php

use App\Http\Controllers\MatchesController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

// Cada ruta valida su permiso en el servidor: ocultar el boton no es suficiente

Route::get('/matches' , [MatchesController::class, 'index'])
     ->name('matches.index')
     ->middleware(AuthorizedMiddleware::class . ':matches.show');

Route::get('/matches/create' , [MatchesController::class, 'create'])
     ->name('matches.create')
     ->middleware(AuthorizedMiddleware::class . ':matches.create');

Route::get('/matches/edit/{id}' , [MatchesController::class, 'edit'])
     ->name('matches.edit')
     ->middleware(AuthorizedMiddleware::class . ':matches.update');

// Va de ultimo entre los GET: si estuviera antes, '/matches/create'
// entraria aqui tomando 'create' como si fuera un id.
Route::get('/matches/{id}' , [MatchesController::class, 'show'])
     ->name('matches.show')
     ->middleware(AuthorizedMiddleware::class . ':matches.show');

Route::post('/matches/store' , [MatchesController::class, 'store'])
     ->name('matches.store')
     ->middleware(AuthorizedMiddleware::class . ':matches.create');

Route::put('/matches/update' , [MatchesController::class, 'update'])
     ->name('matches.update')
     ->middleware(AuthorizedMiddleware::class . ':matches.update');

Route::delete('/matches/delete/{id}' , [MatchesController::class, 'delete'])
     ->name('matches.delete')
     ->middleware(AuthorizedMiddleware::class . ':matches.delete');
