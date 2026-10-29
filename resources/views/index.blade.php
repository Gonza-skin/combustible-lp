<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#203a45">
    <meta name="description" content="Consulta estaciones de servicio y el estado del abastecimiento de combustible en el municipio de La Paz.">
    <title>Combustible La Paz — Información para moverte mejor</title>
    @include('partials.tema-head')
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}?v={{ filemtime(public_path('css/landing.css')) }}">
</head>
<body class="tema-pagina landing-page">
    @include('partials.tema-body')

    <header class="home-header">
        <a class="home-brand" href="{{ route('home') }}" aria-label="Combustible La Paz, inicio">
            <span class="home-brand__mark" aria-hidden="true">
                <img src="{{ asset('images/logo-combustible-la-paz.png') }}" alt="">
            </span>
            <span>Combustible <b>La Paz</b></span>
        </a>

        <nav class="home-nav" aria-label="Navegación principal">
            <a href="#inicio">Inicio</a>
            <a href="#mapa">Mapa</a>
            <a href="#como-funciona">Cómo funciona</a>
        </nav>

        <div class="home-header__actions">
            <a class="home-login" href="{{ route('login') }}">Iniciar sesión</a>
            <a class="home-register" href="{{ route('registro') }}">Crear cuenta</a>
        </div>
    </header>

    <main>
        <section class="home-hero" id="inicio">
            <div class="home-hero__copy">
                <p class="eyebrow"><span></span> Municipio de La Paz</p>
                <h1>Combustible<br>con información.<br><em>La Paz en movimiento.</em></h1>
                <p class="home-hero__intro">
                    Revisa el estado de las estaciones y encuentra información útil
                    para planificar mejor tu recorrido.
                </p>
                <div class="home-hero__actions">
                    <a class="home-button home-button--light" href="#mapa">
                        Explorar estaciones
                        <span aria-hidden="true">→</span>
                    </a>
                    <a class="home-button home-button--outline" href="{{ route('login') }}">Ingresar al sistema</a>
                </div>
                <div class="home-hero__trust">
                    <span class="status-dot" aria-hidden="true"></span>
                    Información de {{ $estaciones->count() }} {{ $estaciones->count() === 1 ? 'estación activa' : 'estaciones activas' }}
                </div>
            </div>

            <div class="home-hero__art" aria-label="Ilustración de La Paz y sus montañas">
                <div class="hero-sun"></div>
                <div class="hero-contour hero-contour--one"></div>
                <div class="hero-contour hero-contour--two"></div>
                <svg class="hero-city" viewBox="0 0 680 390" fill="none" role="img" aria-hidden="true">
                    <path d="m0 216 88-94 55 59 71-121 81 105 53-65 105 118 70-76 52 73 105-85v260H0V216Z" fill="#294955"/>
                    <path d="m0 261 92-82 51 50 80-72 79 69 73-45 77 60 70-60 60 53 98-62v178H0V261Z" fill="#315864"/>
                    <path d="m0 306 98-58 57 42 78-51 90 55 59-38 72 42 77-52 67 42 82-43v107H0V306Z" fill="#3c6b70"/>
                    <g stroke="#f3f0e8" stroke-opacity=".68" stroke-width="2">
                        <path d="M55 330h49v-37h34v37h39v-63h31v63h47v-45h38v45h51v-77h37v77h40v-35h31v35h51v-58h36v58h44v-39h40v39h57"/>
                        <path d="M82 313v-9m18 9v-9m81 9v-12m31 12v-11m89 11v-10m62 10v-16m81 16v-8m84 8v-13m70 13v-9"/>
                    </g>
                    <path d="M15 356c90-27 180-33 277-17 110 18 226 18 373-20" stroke="#4bb8b0" stroke-width="3" stroke-linecap="round"/>
                    <circle cx="336" cy="276" r="8" fill="#d9b05a"/>
                    <circle cx="336" cy="276" r="16" stroke="#d9b05a" stroke-opacity=".4" stroke-width="2"/>
                </svg>
                <div class="hero-note">
                    <span class="hero-note__icon" aria-hidden="true">⌖</span>
                    <span><b>La Paz, Bolivia</b><small>Información en un solo lugar</small></span>
                </div>
                <span class="hero-caption">Una ciudad que avanza, mejor conectada.</span>
            </div>

            <div class="home-hero__metrics" aria-label="Resumen de estaciones">
                <div><strong>{{ $estaciones->count() }}</strong><span>estaciones activas</span></div>
                <div><strong>{{ $estacionesDisponibles }}</strong><span>con estado normal</span></div>
                <div class="metrics-accent"><strong>{{ $estacionesConCoordenadas->count() }}</strong><span>ubicadas en el mapa</span></div>
            </div>
        </section>

        <section class="home-benefits" id="como-funciona" aria-label="Qué puedes consultar">
            <article class="benefit-card">
                <span class="benefit-icon benefit-icon--teal" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M20 10c0 5-8 11-8 11S4 15 4 10a8 8 0 1 1 16 0Z" stroke="currentColor" stroke-width="1.8"/><circle cx="12" cy="10" r="2.5" stroke="currentColor" stroke-width="1.8"/></svg>
                </span>
                <div><h2>Encuentra estaciones</h2><p>Ubica puntos de servicio y consulta su dirección en el mapa.</p></div>
            </article>
            <article class="benefit-card">
                <span class="benefit-icon benefit-icon--gold" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M4 19V5m0 14h16M8 15l3-4 3 2 5-7" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <div><h2>Consulta el estado</h2><p>Revisa el estado registrado para cada estación activa.</p></div>
            </article>
            <article class="benefit-card">
                <span class="benefit-icon benefit-icon--coral" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none"><path d="M21 11.5a8.4 8.4 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.4 8.4 0 0 1-3.8-.9L3 21l1.9-5.7a8.4 8.4 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.4 8.4 0 0 1 3.8-.9h.5a8.5 8.5 0 0 1 8 8v.5Z" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <div><h2>Participa</h2><p>Inicia sesión para compartir reportes y ayudar a la comunidad.</p></div>
            </article>
        </section>

        <section class="home-stations" id="mapa">
            <div class="section-heading">
                <div>
                    <p class="eyebrow eyebrow--dark">Explora La Paz</p>
                    <h2>Estaciones cercanas</h2>
                    <p>Consulta ubicaciones y el estado informado por el sistema.</p>
                </div>
                <a class="text-link" href="{{ route('login') }}">Ver más en mi cuenta <span aria-hidden="true">→</span></a>
            </div>

            <div class="station-layout">
                <div class="station-map">
                    @if ($estacionesConCoordenadas->isNotEmpty())
                        <x-mapa :puntos="$estacionesConCoordenadas" alto="440px" />
                    @else
                        <div class="map-empty">
                            <span class="benefit-icon benefit-icon--teal" aria-hidden="true">⌖</span>
                            <h3>El mapa estará disponible pronto</h3>
                            <p>Aún no hay estaciones activas con ubicación registrada.</p>
                        </div>
                    @endif
                </div>

                <aside class="station-list" aria-label="Estaciones activas">
                    <div class="station-list__heading">
                        <h3>Estado de estaciones</h3>
                        <span>{{ $estaciones->count() }}</span>
                    </div>
                    @forelse ($estaciones->take(5) as $estacion)
                        @php
                            $estado = match ($estacion->ESD_EST) {
                                'normal' => ['Disponible', 'station-status--available'],
                                'nivel_bajo' => ['En escasez', 'station-status--low'],
                                'sin_combustible' => ['Agotado', 'station-status--empty'],
                                default => ['Sin actualizar', 'station-status--unknown'],
                            };
                        @endphp
                        <article class="station-item">
                            <span class="station-item__pin" aria-hidden="true">⌖</span>
                            <div class="station-item__details">
                                <h4>{{ $estacion->NOM_EST }}</h4>
                                <p>{{ $estacion->DIR_EST ?: ($estacion->ZON_EST ?: 'Municipio de La Paz') }}</p>
                                <span class="station-status {{ $estado[1] }}"><i></i>{{ $estado[0] }}</span>
                            </div>
                        </article>
                    @empty
                        <div class="station-empty">
                            <p>Por el momento no hay estaciones activas para mostrar.</p>
                        </div>
                    @endforelse
                    @if ($estaciones->count() > 5)
                        <p class="station-list__more">Mostrando 5 de {{ $estaciones->count() }} estaciones.</p>
                    @endif
                </aside>
            </div>
        </section>

        <section class="home-cta">
            <div>
                <p class="eyebrow">La ciudad la hacemos entre todos</p>
                <h2>Comparte información útil<br>con tu comunidad.</h2>
            </div>
            <a class="home-button home-button--light" href="{{ route('registro') }}">Crear mi cuenta <span aria-hidden="true">→</span></a>
        </section>
    </main>

    <footer class="home-footer">
        <a class="home-brand home-brand--footer" href="{{ route('home') }}">
            <span class="home-brand__mark" aria-hidden="true">
                <img src="{{ asset('images/logo-combustible-la-paz.png') }}" alt="">
            </span>
            <span>Combustible <b>La Paz</b></span>
        </a>
        <p>Información para movernos mejor por La Paz.</p>
        <span class="home-footer__copyright">© {{ date('Y') }} Municipio de La Paz</span>
    </footer>
</body>
</html>
