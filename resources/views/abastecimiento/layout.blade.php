<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('titulo', 'Abastecimiento') - Sistema de Abastecimiento La Paz</title>
<link rel="stylesheet" href="{{ asset('css/sistema.css') }}">
@include('partials.tema-head')
</head>
<body>
@include('partials.tema-body')
@php
    $rolActual = \Illuminate\Support\Facades\DB::table('rol')->where('COD_ROL', auth()->user()->COD_ROL)->value('DES_ROL');
    $menu = [
        ['Inicio', '/abastecimiento', 'abastecimiento', null],
        ['Panel', '/abastecimiento/panel', 'abastecimiento/panel', null],
        ['Solicitudes', '/abastecimiento/solicitudes', 'abastecimiento/solicitudes*', ['encargado_estacion','sub_administrador','administrador']],
        ['Gestión', '/abastecimiento/gestion', 'abastecimiento/gestion*', ['sub_administrador','administrador']],
        ['Historial', '/abastecimiento/historial', 'abastecimiento/historial*', ['sub_administrador','administrador','supervisor']],
        ['Plantas', '/abastecimiento/plantas', 'abastecimiento/plantas*', ['administrador_deposito','administrador']],
        ['Tanques', '/abastecimiento/tanques', 'abastecimiento/tanques*', ['administrador_deposito','administrador']],
    ];
@endphp
<div class="app">
    <header class="app__barra">
        <a class="app__marca" href="{{ url('/abastecimiento') }}">Sistema de Abastecimiento — La Paz</a>
        <div class="app__usuario">
            <span>{{ auth()->user()->NOM_USU }}</span>
            <span class="rol-chip">{{ $rolActual }}</span>
            <form method="POST" action="{{ route('logout') }}">@csrf<button class="btn btn--claro btn--sm" type="submit">Cerrar sesión</button></form>
        </div>
    </header>
    <nav class="menu" aria-label="Menú principal">
        <div class="menu__titulo">Abastecimiento</div>
        @foreach($menu as $m)
            @if($m[3] === null || in_array($rolActual, $m[3], true))
                <a href="{{ url($m[1]) }}" class="{{ request()->is($m[2]) ? 'activo' : '' }}">{{ $m[0] }}</a>
            @endif
        @endforeach
        <div class="menu__titulo">Sistema</div>
        <a href="{{ url('/dashboard') }}">Panel general</a>
    </nav>
    <main class="contenido mod-abast">
        @if(session('ok'))<div class="ok">{{ session('ok') }}</div>@endif
        @if($errors->any())
            <div class="err"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
        @endif
        <section class="tarjeta">@yield('contenido')</section>
    </main>
</div>
</body>
</html>