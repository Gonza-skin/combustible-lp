<script>document.body.classList.add('con-sidebar');</script>
<nav class="menu sidebar-dashboard" aria-label="Menú principal">
    <a class="sidebar-dashboard__marca" href="{{ route('dashboard') }}">
        <img class="app__logo" src="{{ asset('images/logo-combustible-la-paz.png') }}" alt="">
        Sistema de Abastecimiento — La Paz
    </a>
    @auth
        @include('partials.dashboard-menu')
    @endauth
</nav>
