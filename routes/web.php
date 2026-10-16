<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\ExistenciaController;
use App\Http\Controllers\CisternaController;
use App\Http\Controllers\ConductorController;
use App\Http\Controllers\MantenimientoCisternaController;

// Inicio
Route::get('/', [HomeController::class, 'index'])->name('home');

// Autenticación
Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Panel principal
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')->name('dashboard');

// Estaciones
Route::resource('estaciones', EstacionController::class)
    ->parameters(['estaciones' => 'estacion'])->except(['show']);

// Existencias
Route::get('/existencias', [ExistenciaController::class, 'index'])->name('existencias.index');
Route::get('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'ajustar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.ajustar');
Route::post('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'guardar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.guardar');

// cisternas, conductores y mantenimientos
Route::resource('cisternas', CisternaController::class)->parameters(['cisternas' => 'cisterna']);
Route::resource('conductores', ConductorController::class)->parameters(['conductores' => 'conductor']);
Route::resource('mantenimiento-cisternas', MantenimientoCisternaController::class)
    ->parameters(['mantenimiento-cisternas' => 'mantenimiento_cisterna']);

/*
// Administración (activar cuando existan los controladores y middleware)
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TipoCombustibleController;

Route::middleware(['auth', 'role:administrador,administrador_sistema'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('roles', RolController::class);
    Route::resource('tipos-combustible', TipoCombustibleController::class);
});
*/
