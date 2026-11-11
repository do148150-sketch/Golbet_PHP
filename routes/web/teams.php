<?php

use App\Http\Controllers\TeamsController;
use App\Http\Middleware\AuthorizedMiddleware;
use Illuminate\Support\Facades\Route;

// Cada ruta valida su permiso en el servidor: ocultar el boton no es suficiente

Route::get('/teams' , [TeamsController::class, 'index'])
     ->name('teams.index')
     ->middleware(AuthorizedMiddleware::class . ':teams.show');

Route::get('/teams/create' , [TeamsController::class, 'create'])
     ->name('teams.create')
     ->middleware(AuthorizedMiddleware::class . ':teams.create');

Route::get('/teams/edit/{id}' , [TeamsController::class, 'edit'])
     ->name('teams.edit')
     ->middleware(AuthorizedMiddleware::class . ':teams.update');

Route::post('/teams/store' , [TeamsController::class, 'store'])
     ->name('teams.store')
     ->middleware(AuthorizedMiddleware::class . ':teams.create');

Route::put('/teams/update' , [TeamsController::class, 'update'])
     ->name('teams.update')
     ->middleware(AuthorizedMiddleware::class . ':teams.update');

Route::delete('/teams/delete/{id}' , [TeamsController::class, 'delete'])
     ->name('teams.delete')
     ->middleware(AuthorizedMiddleware::class . ':teams.delete');
