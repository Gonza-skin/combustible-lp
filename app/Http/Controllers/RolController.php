<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Illuminate\View\View;

/** Catálogo de roles: solo consulta (los roles los define el sistema, no se crean desde pantalla). */
class RolController extends Controller
{
    public function index(): View
    {
        $roles = Rol::withCount('usuarios')->orderBy('COD_ROL')->get();

        return view('roles.index', compact('roles'));
    }
}