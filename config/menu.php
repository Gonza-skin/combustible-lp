<?php

return [
    ['titulo' => 'Inicio', 'ruta' => 'dashboard', 'roles' => []],

    // ---- Administración (Gonzalo) ----
    ['grupo' => 'Administración', 'titulo' => 'Usuarios', 'ruta' => 'usuarios.index', 'roles' => ['administrador']],
    ['grupo' => 'Administración', 'titulo' => 'Roles', 'ruta' => 'roles.index', 'roles' => ['administrador']],
    ['grupo' => 'Administración', 'titulo' => 'Tipos de combustible', 'ruta' => 'tipos-combustible.index', 'roles' => ['administrador']],

    // ---- Ejemplo para los demás (descomentar y ajustar) ----
    // ['grupo' => 'Estaciones', 'titulo' => 'Estaciones', 'ruta' => 'estaciones.index', 'roles' => ['administrador', 'supervisor']],
];