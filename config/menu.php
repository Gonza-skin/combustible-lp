<?php

return [
    ['titulo' => 'Inicio', 'ruta' => 'dashboard', 'roles' => []],

    // admin
    ['grupo' => 'Administración', 'titulo' => 'Usuarios', 'ruta' => 'usuarios.index', 'roles' => ['administrador']],
    ['grupo' => 'Administración', 'titulo' => 'Roles', 'ruta' => 'roles.index', 'roles' => ['administrador']],
    ['grupo' => 'Administración', 'titulo' => 'Tipos de combustible', 'ruta' => 'tipos-combustible.index', 'roles' => ['administrador']],

    // ---- Estaciones y existencias ----
    ['grupo' => 'Estaciones', 'titulo' => 'Estaciones', 'ruta' => 'estaciones.index', 'roles' => []],
    ['grupo' => 'Estaciones', 'titulo' => 'Existencias', 'ruta' => 'existencias.index', 'roles' => ['encargado_estacion', 'administrador']],
    ['grupo' => 'Estaciones', 'titulo' => 'Movimientos', 'ruta' => 'movimientos.index', 'roles' => ['encargado_estacion', 'administrador', 'supervisor']],
    ['grupo' => 'Estaciones', 'titulo' => 'Recomendación de abastecimiento', 'ruta' => 'recomendaciones.index', 'roles' => ['sub_administrador', 'administrador']],
        // ---- Investigación Operativa II ----
    ['grupo' => 'Investigación Operativa', 'titulo' => 'Teoría de decisiones', 'ruta' => 'decisiones.index', 'roles' => ['sub_administrador', 'administrador']],
    ['grupo' => 'Investigación Operativa', 'titulo' => 'Predicción Markov', 'ruta' => 'markov.index', 'roles' => ['sub_administrador', 'administrador', 'supervisor']],
        ['grupo' => 'Investigación Operativa', 'titulo' => 'Laboratorio (demo)', 'ruta' => 'laboratorio.index', 'roles' => ['sub_administrador', 'administrador', 'supervisor']],

    ];