<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EstacionController;
use App\Http\Controllers\ExistenciaController;


// inicio
Route::get('/', [HomeController::class, 'index'])->name('home');

// autenticacion
Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
->middleware('auth')
->name('dashboard');

// estaciones
Route::resource('estaciones', EstacionController::class)
->parameters(['estaciones' => 'estacion'])
->except(['show']);

// existencias
Route::get('/existencias', [ExistenciaController::class, 'index'])->name('existencias.index');
Route::get('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'ajustar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.ajustar');
Route::post('/existencias/{estacion}/{combustible}/ajustar', [ExistenciaController::class, 'guardar'])
    ->whereNumber(['estacion', 'combustible'])->name('existencias.guardar');

// Route::view('/login', 'auth.login')->name('login');
// Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

/*
// ---- Administración (descomentar cuando existan los controladores y el middleware) ----
// Al activarlo, agrega arriba estos tres "use":
// use App\Http\Controllers\UsuarioController;
// use App\Http\Controllers\RolController;
// use App\Http\Controllers\TipoCombustibleController;

Route::middleware(['auth', 'role:administrador,administrador_sistema'])->group(function () {
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('roles', RolController::class);
    Route::resource('tipos-combustible', TipoCombustibleController::class);
});
*/

