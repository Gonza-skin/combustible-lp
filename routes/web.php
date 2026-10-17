<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\UsuarioController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/registro', [RegistroController::class, 'create'])->middleware('guest')->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->middleware('guest');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

// ---- Administración solo rol administrador ----
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');

    Route::resource('usuarios', UsuarioController::class)->except('show');
});

// ---- Estaciones, existencias, movimientos y recomendaciones 

// Ver estaciones: cualquier usuario con sesion
Route::get('/estaciones', [EstacionController::class, 'index'])
    ->middleware('auth')
    ->name('estaciones.index');

// Crear, editar y eliminar estaciones solo administrador
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::resource('estaciones', EstacionController::class)
        ->parameters(['estaciones' => 'estacion'])
        ->except(['index', 'show']);
});

// Existencias y ajuste encargado de estacion y administrador
Route::middleware(['auth', 'role:encargado_estacion,administrador'])->group(function () {
    Route::get('/existencias', [ExistenciaController::class, 'index'])->name('existencias.index');
    Route::get('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'ajustar'])
        ->whereNumber(['estacion', 'combustible'])->name('existencias.ajustar');
    Route::post('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'guardar'])
        ->whereNumber(['estacion', 'combustible'])->name('existencias.guardar');
});

// Movimientos (solo lectura) encargado, administrador y supervisor
Route::get('/movimientos', [\App\Http\Controllers\MovimientoController::class, 'index'])
    ->middleware(['auth', 'role:encargado_estacion,administrador,supervisor'])
    ->name('movimientos.index');

// Recomendacion de abastecimiento sub_administrador y administrador
Route::get('/recomendaciones', [\App\Http\Controllers\RecomendacionController::class, 'index'])
    ->middleware(['auth', 'role:sub_administrador,administrador'])
    ->name('recomendaciones.index');
require __DIR__ . '/abastecimiento.php';
