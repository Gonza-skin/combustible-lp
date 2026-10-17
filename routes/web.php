<?php

<<<<<<< HEAD
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
=======
use App\Http\Controllers\AlertaController;
use App\Http\Controllers\AutomovilController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\IncidenciaController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ReporteCiudadanoAdminController;
use App\Http\Controllers\ReporteCiudadanoController;
use App\Http\Controllers\VentaCombustibleController;
use App\Http\Middleware\AlertaAccess;
use App\Http\Middleware\AutomovilAccess;
use App\Http\Middleware\CiudadanoAccess;
use App\Http\Middleware\IncidenciaAccess;
use App\Http\Middleware\ReporteCiudadanoAdminAccess;
use App\Http\Middleware\VentaCombustibleAccess;
use Illuminate\Support\Facades\Route;
>>>>>>> origin/Stephanie

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

<<<<<<< HEAD
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
=======
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

Route::middleware(['auth', VentaCombustibleAccess::class])
    ->prefix('ventas')
    ->name('ventas.')
    ->group(function () {
        Route::get('/', [VentaCombustibleController::class, 'index'])->name('index');
        Route::get('/create', [VentaCombustibleController::class, 'create'])->name('create');
        Route::post('/', [VentaCombustibleController::class, 'store'])->name('store');
        Route::get('/{venta}', [VentaCombustibleController::class, 'show'])->name('show');
        Route::get('/{venta}/edit', [VentaCombustibleController::class, 'edit'])->name('edit');
        Route::put('/{venta}', [VentaCombustibleController::class, 'update'])->name('update');
        Route::delete('/{venta}', [VentaCombustibleController::class, 'destroy'])->name('destroy');
    });

Route::middleware(['auth', CiudadanoAccess::class])
    ->prefix('reportes-ciudadanos')
    ->name('reportes-ciudadanos.')
    ->group(function () {
        Route::get('/crear', [ReporteCiudadanoController::class, 'create'])->name('create');
        Route::post('/', [ReporteCiudadanoController::class, 'store'])->name('store');
    });

Route::middleware(['auth', ReporteCiudadanoAdminAccess::class])
    ->get('/reportes-ciudadanos', [ReporteCiudadanoAdminController::class, 'index'])
    ->name('reportes-ciudadanos.index');

Route::middleware(['auth', IncidenciaAccess::class])
    ->prefix('incidencias')
    ->name('incidencias.')
    ->group(function () {
        Route::get('/', [IncidenciaController::class, 'index'])->name('index');
        Route::get('/create', [IncidenciaController::class, 'create'])->name('create');
        Route::post('/', [IncidenciaController::class, 'store'])->name('store');
        Route::get('/{incidencia}', [IncidenciaController::class, 'show'])->name('show');
        Route::get('/{incidencia}/edit', [IncidenciaController::class, 'edit'])->name('edit');
        Route::put('/{incidencia}', [IncidenciaController::class, 'update'])->name('update');
        Route::delete('/{incidencia}', [IncidenciaController::class, 'destroy'])->name('destroy');
    });

Route::middleware(['auth', AlertaAccess::class])
    ->prefix('alertas')
    ->name('alertas.')
    ->group(function () {
        Route::get('/', [AlertaController::class, 'index'])->name('index');
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
>>>>>>> origin/Stephanie
