<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

include('auth.php');
include('web/teams.php');
include('web/users.php');
include('web/matches.php');
include('web/profile.php');
include('web/bets.php');
