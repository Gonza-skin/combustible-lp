<?php

use App\Http\Controllers\AutomovilController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Middleware\AutomovilAccess;
use Illuminate\Support\Facades\Route;

Route::view('/login', 'auth.login')->middleware('guest')->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::view('/login', 'auth.login')->name('login');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::middleware(['auth', AutomovilAccess::class])
    ->prefix('automoviles')
    ->name('automoviles.')
    ->group(function () {
        Route::get('/', [AutomovilController::class, 'index'])->name('index');
        Route::get('/create', [AutomovilController::class, 'create'])->name('create');
        Route::post('/', [AutomovilController::class, 'store'])->name('store');
        Route::get('/{automovil}', [AutomovilController::class, 'show'])->name('show');
        Route::get('/{automovil}/edit', [AutomovilController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '/{automovil}', [AutomovilController::class, 'update'])->name('update');
        Route::delete('/{automovil}', [AutomovilController::class, 'destroy'])->name('destroy');
    });

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
