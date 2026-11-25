<?php

use App\Http\Controllers\BetsController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

// Cada ruta valida su permiso en el servidor: ocultar el boton no es suficiente

Route::get('/bets' , [BetsController::class, 'index'])
     ->name('bets.index')
     ->middleware(AuthorizedMiddleware::class . ':bets.show');

Route::get('/bets/{id}' , [BetsController::class, 'show'])
     ->name('bets.show')
     ->middleware(AuthorizedMiddleware::class . ':bets.show');

Route::post('/bets/store' , [BetsController::class, 'store'])
     ->name('bets.store')
     ->middleware(AuthorizedMiddleware::class . ':bets.create');
