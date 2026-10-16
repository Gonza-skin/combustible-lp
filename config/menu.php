<?php

/*
| Menú lateral del sistema. Una línea por pantalla.
|   'titulo' → texto que se ve en el menú
|   'ruta'   → nombre de la ruta (ej. 'cisternas.index')
|   'roles'  → roles que la ven (igual que en role:...). [] = todos los que inicien sesión
|   'grupo'  → (opcional) título de la sección
*/

return [
    ['titulo' => 'Inicio', 'ruta' => 'dashboard', 'roles' => []],

    // ---- Administración (Gonzalo) ----
    ['grupo' => 'Administración', 'titulo' => 'Usuarios', 'ruta' => 'usuarios.index', 'roles' => ['administrador', 'administrador_sistema']],
    ['grupo' => 'Administración', 'titulo' => 'Roles', 'ruta' => 'roles.index', 'roles' => ['administrador']],
    ['grupo' => 'Administración', 'titulo' => 'Tipos de combustible', 'ruta' => 'tipos-combustible.index', 'roles' => ['administrador']],

    // ---- Ejemplo para los demás (descomentar y ajustar) ----
    // ['grupo' => 'Estaciones', 'titulo' => 'Estaciones', 'ruta' => 'estaciones.index', 'roles' => ['administrador', 'supervisor']],
];