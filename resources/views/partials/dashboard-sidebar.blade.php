<script>document.body.classList.add('con-sidebar');</script>
<nav class="menu sidebar-dashboard" aria-label="Menú principal">
    <a class="sidebar-dashboard__marca" href="{{ route('dashboard') }}">Sistema de Abastecimiento — La Paz</a>
    @auth
        @include('partials.dashboard-menu')
    @endauth
</nav>
