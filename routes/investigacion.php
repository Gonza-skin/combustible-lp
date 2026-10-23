<?php

use App\Http\Controllers\InvestigacionOperativa\DecisionesController;
use App\Http\Controllers\InvestigacionOperativa\MarkovController;
use Illuminate\Support\Facades\Route;

// Investigación Operativa II: Teoría de Decisiones y Cadenas de Markov.
Route::middleware(['auth', 'role:sub_administrador,administrador'])->group(function () {
    Route::get('/investigacion/decisiones', [DecisionesController::class, 'index'])->name('decisiones.index');
});

Route::middleware(['auth', 'role:sub_administrador,administrador,supervisor'])->group(function () {
    Route::get('/investigacion/markov', [MarkovController::class, 'index'])->name('markov.index');
    Route::view('/investigacion/laboratorio', 'investigacion.laboratorio')->name('laboratorio.index');
});

Route::middleware(['auth', 'role:sub_administrador,administrador,supervisor'])->group(function () {
    Route::get('/investigacion/markov', [MarkovController::class, 'index'])->name('markov.index');
});