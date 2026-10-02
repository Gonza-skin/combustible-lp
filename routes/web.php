<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    return 'formulario aqui xd';
})->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');