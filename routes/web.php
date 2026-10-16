<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\TipoCombustibleController;
use App\Http\Controllers\UsuarioController;

// ---- Público ----
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::get('/registro', [RegistroController::class, 'create'])->middleware('guest')->name('registro');
Route::post('/registro', [RegistroController::class, 'store'])->middleware('guest');

// ---- Con sesión (cualquier rol) ----
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');
    Route::get('/solo-admin', fn () => 'Entraste como administrador')
    ->middleware(['auth', 'role:administrador']);
// ---- Administración: catálogos (solo rol administrador) ----
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::get('/roles', [RolController::class, 'index'])->name('roles.index');

    Route::resource('tipos-combustible', TipoCombustibleController::class)
        ->only(['index', 'edit', 'update'])
        ->parameters(['tipos-combustible' => 'tipo']);
});
// ---- Administración: usuarios (solo rol administrador) ----
Route::middleware(['auth', 'role:administrador'])->group(function () {
    Route::resource('usuarios', UsuarioController::class)->except('show');
});