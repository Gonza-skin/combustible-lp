<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\ExistenciaController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', function () {
    return 'formulario aqui xd';
})->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('estaciones', EstacionController::class)
    ->parameters(['estaciones' => 'estacion'])
    ->except(['show']);

Route::get('/existencias', [ExistenciaController::class, 'index'])->name('existencias.index');
Route::get('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'ajustar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.ajustar');
Route::post('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'guardar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.guardar');