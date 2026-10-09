<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstacionController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    return 'formulario aqui xd';
})->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('estaciones', EstacionController::class)
    ->parameters(['estaciones' => 'estacion'])
    ->except(['show']);