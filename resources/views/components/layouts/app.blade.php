{{--
    Layout principal del sistema.
    Uso:  <x-layouts.app title="Nombre de la página"> ...contenido... </x-layouts.app>
    El menú lateral sale de config/menu.php (filtrado por el rol del usuario).
--}}
@props(['title' => null])
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ? $title.' — ' : '' }}Sistema de Abastecimiento La Paz</title>
    <link rel="stylesheet" href="{{ asset('css/sistema.css') }}">
    @include('partials.tema-head')
</head>
<body>
@include('partials.tema-body')
<div class="app">
    <header class="app__barra">
        <a class="app__marca" href="{{ auth()->check() ? route('dashboard') : route('home') }}">
            Sistema de Abastecimiento — La Paz
        </a>
        @auth
            <div class="app__usuario">
                <span>{{ auth()->user()->NOM_USU }}</span>
                @if (auth()->user()->rol)
                    <span class="rol-chip">{{ auth()->user()->rol->nombre }}</span>
                @endif
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-button variant="claro" class="btn--sm">Cerrar sesión</x-button>
                </form>
            </div>
        @endauth
    </header>

    <nav class="menu" aria-label="Menú principal">
        @auth
            @include('partials.dashboard-menu')
        @endauth
    </nav>

    <main class="contenido">
        <x-alert />
        {{ $slot }}
    </main>
</div>
</body>
</html>