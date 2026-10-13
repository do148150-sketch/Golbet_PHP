<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

// PARTE 2 (CRUD de Equipos): descomentar cuando exista routes/web/teams.php
// include('web/teams.php');
