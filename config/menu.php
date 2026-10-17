<?php

return [
    ['titulo' => 'Inicio', 'ruta' => 'dashboard', 'roles' => []],

    // admin
    ['grupo' => 'Administración', 'titulo' => 'Usuarios', 'ruta' => 'usuarios.index', 'roles' => ['administrador']],
    ['grupo' => 'Administración', 'titulo' => 'Roles', 'ruta' => 'roles.index', 'roles' => ['administrador']],
  
];