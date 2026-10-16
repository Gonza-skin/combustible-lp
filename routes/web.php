<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/registro', [RegistroController::class, 'create'])->middleware('guest')->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/solo-admin', fn () => 'Entraste como administrador')
    ->middleware(['auth', 'role:administrador,administrador_sistema']);

Route::resource('estaciones', EstacionController::class)
    ->parameters(['estaciones' => 'estacion'])
    ->except(['show']);

Route::get('/existencias', [ExistenciaController::class, 'index'])->name('existencias.index');
Route::get('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'ajustar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.ajustar');
Route::post('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'guardar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.guardar');

Route::get('/movimientos', [\App\Http\Controllers\MovimientoController::class, 'index'])->name('movimientos.index');

Route::get('/recomendaciones', [\App\Http\Controllers\RecomendacionController::class, 'index'])
    ->middleware(['auth', 'role:sub_administrador,administrador'])
    ->name('recomendaciones.index');