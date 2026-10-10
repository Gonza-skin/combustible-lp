<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/login', 'auth.login')->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
require __DIR__ . '/abastecimiento.php';
