<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\LoginController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::resource('estaciones', EstacionController::class)
    ->parameters(['estaciones' => 'estacion'])
    ->except(['show']);

Route::get('/existencias', [ExistenciaController::class, 'index'])->name('existencias.index');
Route::get('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'ajustar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.ajustar');
Route::post('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'guardar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.guardar');
Route::get('/movimientos', [\App\Http\Controllers\MovimientoController::class, 'index'])->name('movimientos.index');