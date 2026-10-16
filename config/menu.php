<?php

return [
    ['titulo' => 'Inicio', 'ruta' => 'dashboard', 'roles' => []],

    // ---- Administración (Gonzalo) ----
    ['grupo' => 'Administración', 'titulo' => 'Usuarios', 'ruta' => 'usuarios.index', 'roles' => ['administrador', 'administrador_sistema']],
    ['grupo' => 'Administración', 'titulo' => 'Roles', 'ruta' => 'roles.index', 'roles' => ['administrador']],
    ['grupo' => 'Administración', 'titulo' => 'Tipos de combustible', 'ruta' => 'tipos-combustible.index', 'roles' => ['administrador']],

    // ---- Estaciones y existencias (Angela) ----
    ['grupo' => 'Estaciones', 'titulo' => 'Estaciones', 'ruta' => 'estaciones.index', 'roles' => []],
    ['grupo' => 'Estaciones', 'titulo' => 'Existencias', 'ruta' => 'existencias.index', 'roles' => ['encargado_estacion', 'administrador']],
    ['grupo' => 'Estaciones', 'titulo' => 'Movimientos', 'ruta' => 'movimientos.index', 'roles' => ['encargado_estacion', 'administrador', 'supervisor']],
    ['grupo' => 'Estaciones', 'titulo' => 'Recomendación de abastecimiento', 'ruta' => 'recomendaciones.index', 'roles' => ['sub_administrador', 'administrador']],
];