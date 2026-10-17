<?php

use App\Http\Controllers\Abastecimiento\PlantaController;
use App\Http\Controllers\Abastecimiento\TanqueController;
use App\Http\Middleware\VerificarRol;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('abastecimiento')->name('abast.')->group(function () {
    Route::view('/', 'abastecimiento.inicio')->name('inicio');
    Route::get('panel', [\App\Http\Controllers\Abastecimiento\PanelController::class, 'index'])->name('panel');


    // Plantas y tanques: administrador_deposito, administrador
    Route::middleware([VerificarRol::class . ':administrador_deposito,administrador'])->group(function () {
        Route::resource('plantas', PlantaController::class)->except('show')->parameters(['plantas' => 'planta']);
        Route::resource('tanques', TanqueController::class)->except('show')->parameters(['tanques' => 'tanque']);
    });

    // (acá van después: solicitudes, gestión e historial)

    // Solicitudes
    $ctl = \App\Http\Controllers\Abastecimiento\SolicitudController::class;
    Route::middleware([VerificarRol::class . ':encargado_estacion'])->group(function () use ($ctl) {
        Route::get('solicitudes/crear', [$ctl, 'create'])->name('solicitudes.create');
        Route::post('solicitudes', [$ctl, 'store'])->name('solicitudes.store');
        Route::get('solicitudes/{solicitud}/editar', [$ctl, 'edit'])->name('solicitudes.edit');
        Route::put('solicitudes/{solicitud}', [$ctl, 'update'])->name('solicitudes.update');
        Route::patch('solicitudes/{solicitud}/cancelar', [$ctl, 'cancelar'])->name('solicitudes.cancelar');
    });
    Route::middleware([VerificarRol::class . ':encargado_estacion,sub_administrador,administrador'])->group(function () use ($ctl) {
        Route::get('solicitudes', [$ctl, 'index'])->name('solicitudes.index');
        Route::get('solicitudes/{solicitud}', [$ctl, 'show'])->name('solicitudes.show');
    });

    // Gestión de abastecimiento: sub_administrador, administrador
    $gctl = \App\Http\Controllers\Abastecimiento\AbastecimientoController::class;
    Route::middleware([VerificarRol::class . ':sub_administrador,administrador'])->prefix('gestion')->name('gestion.')->group(function () use ($gctl) {
        Route::get('/', [$gctl, 'index'])->name('index');
        Route::post('solicitud/{solicitud}/crear', [$gctl, 'crearDesdeSolicitud'])->name('crear');
        Route::get('{abastecimiento}', [$gctl, 'show'])->name('show');
        Route::post('{abastecimiento}/asignar', [$gctl, 'asignarCisterna'])->name('asignar');
        Route::patch('{abastecimiento}/estado', [$gctl, 'cambiarEstado'])->name('estado');
    });

    // Historial (solo lectura): sub_administrador, administrador, supervisor
    Route::middleware([VerificarRol::class . ':sub_administrador,administrador,supervisor'])->group(function () {
        Route::get('historial', [\App\Http\Controllers\Abastecimiento\HistorialController::class, 'index'])->name('historial.index');
    });
});