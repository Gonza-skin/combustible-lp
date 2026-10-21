<x-layouts.app title="Teoría de decisiones">
    <style>
        .io-tabla{width:100%;border-collapse:collapse}
        .io-tabla th,.io-tabla td{padding:9px 10px;border-bottom:1px solid var(--borde,#ddd);text-align:left}
        .io-tabla th{font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:var(--texto-suave,#667)}
        .io-tabla input{width:100%;min-width:70px;padding:7px 9px;border:1px solid var(--borde,#ccc);border-radius:9px;background:var(--campo-fondo,#fff);color:inherit}
        .io-mejor{background:color-mix(in srgb,var(--ac,#19d98b) 18%,transparent);font-weight:700}
        .io-bar{height:8px;border-radius:99px;background:var(--borde,#ddd);overflow:hidden;margin-top:5px}
        .io-bar i{display:block;height:100%;border-radius:99px;background:var(--ac,#19d98b);box-shadow:0 0 10px var(--glow,transparent)}
        .io-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:16px;margin-bottom:18px}
        .io-nota{color:var(--texto-suave,#667);font-size:13px;line-height:1.6}
        .io-err{color:var(--rojo,#d64545);margin-bottom:8px}
        .io-veredicto{font-size:22px;font-weight:700;margin:6px 0}
        details.io-det summary{cursor:pointer;color:var(--ac2,#19d98b);font-weight:600}
    </style>

    <x-page-header titulo="Teoría de decisiones"
                   subtitulo="¿A qué estación se envía primero la cisterna disponible? (apoyo al subadministrador)" />

    <div class="stats">
        <x-stat :valor="$candidatas->count()" texto="Estaciones con alerta de reorden" :alerta="$candidatas->count() > 1" />
        <x-stat :valor="$cisternas" texto="Cisternas disponibles" />
    </div>

    @if ($candidatas->count() > $cisternas)
        <div class="alerta alerta--error">Hay más estaciones en alerta ({{ $candidatas->count() }}) que cisternas disponibles ({{ $cisternas }}): este cuadro ayuda a priorizar.</div>
    @endif

    <x-card>
        <div class="tarjeta__titulo"><h2>Tabla de pagos: vehículos atendidos</h2>
            <span class="io-nota">
                <a href="{{ route('decisiones.index') }}">Tabla 1 del informe</a> ·
                <a href="{{ route('decisiones.index', ['ejemplo' => 'contraste']) }}">Ejemplo donde los criterios difieren</a>
            </span>
        </div>
        @foreach ($mensajes as $m)<div class="io-err">{{ $m }}</div>@endforeach

        <form method="GET" action="{{ route('decisiones.index') }}">
            <div style="overflow-x:auto">
            <table class="io-tabla">
                <thead><tr><th>Enviar la cisterna a…</th>@foreach ($estados as $e)<th>{{ $e }}</th>@endforeach</tr></thead>
                <tbody>
                @foreach ($nombres as $i => $nombre)
                    <tr>
                        <td><input name="nombres[{{ $i }}]" value="{{ $nombre }}" required></td>
                        @foreach ($estados as $j => $e)
                            <td><input type="number" min="0" step="any" name="pagos[{{ $i }}][{{ $j }}]" value="{{ $pagos[$i][$j] }}" required></td>
                        @endforeach
                    </tr>
                @endforeach
                    <tr>
                        <td><strong>Probabilidad histórica (%)</strong></td>
                        @foreach ($estados as $j => $e)
                            <td><input type="number" min="0" max="100" step="any" name="probs[{{ $j }}]" value="{{ $probs[$j] }}" required></td>
                        @endforeach
                    </tr>
                </tbody>
            </table>
            </div>
            <p class="io-nota">Las probabilidades deben sumar 100 %. Los pagos de ejemplo son datos ficticios hasta tener historial real.</p>
            <x-button type="submit">Calcular</x-button>
        </form>
    </x-card>

    @if ($resultado)
        @php
            $ve = $resultado['valores_esperados'];
            $mn = $resultado['minimos'];
            $mejorVe = $resultado['mejor_valor_esperado'];
            $mejorW = $resultado['mejor_wald'];
        @endphp
        <div class="io-grid">
            <x-card>
                <div class="io-nota">Criterio del valor esperado</div>
                <div class="io-veredicto">{{ $nombres[$mejorVe] }}</div>
                <div class="io-nota">Atendería en promedio {{ number_format($ve[$mejorVe], 1, ',', '.') }} vehículos.</div>
            </x-card>
            <x-card>
                <div class="io-nota">Criterio de Wald (maximin, conservador)</div>
                <div class="io-veredicto">{{ $nombres[$mejorW] }}</div>
                <div class="io-nota">Aun en el peor escenario atiende {{ number_format($mn[$mejorW], 0, ',', '.') }} vehículos.</div>
            </x-card>
        </div>

        <x-card>
            <div class="tarjeta__titulo"><h2>Resultado por alternativa</h2></div>
            <table class="io-tabla">
                <thead><tr><th>Estación</th><th>Valor esperado</th><th>Peor escenario (Wald)</th></tr></thead>
                <tbody>
                @foreach ($nombres as $i => $nombre)
                    <tr>
                        <td>{{ $nombre }}</td>
                        <td class="{{ $i === $mejorVe ? 'io-mejor' : '' }}">{{ number_format($ve[$i], 1, ',', '.') }}
                            <div class="io-bar"><i style="width:{{ max($ve) > 0 ? round($ve[$i] / max($ve) * 100) : 0 }}%"></i></div></td>
                        <td class="{{ $i === $mejorW ? 'io-mejor' : '' }}">{{ number_format($mn[$i], 0, ',', '.') }}
                            <div class="io-bar"><i style="width:{{ max($mn) > 0 ? round($mn[$i] / max($mn) * 100) : 0 }}%"></i></div></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <p class="io-nota" style="margin-top:12px">
                @if ($resultado['coinciden'])
                    Los dos criterios coinciden: <strong>{{ $nombres[$mejorVe] }}</strong> es la mejor opción tanto en promedio como en el peor escenario.
                @else
                    Los criterios difieren: el valor esperado prefiere <strong>{{ $nombres[$mejorVe] }}</strong> (mejor en promedio), pero Wald prefiere
                    <strong>{{ $nombres[$mejorW] }}</strong> (menos riesgo si la demanda resulta baja). En crisis, cuando no se confía en las probabilidades, conviene Wald.
                @endif
                Esta tabla es solo un apoyo: no reemplaza el criterio del subadministrador.
            </p>
            <details class="io-det">
                <summary>¿Cómo se calculó?</summary>
                <p class="io-nota">
                    <strong>Valor esperado</strong> = Σ pⱼ · pagoᵢⱼ (promedio de vehículos atendidos ponderado por la probabilidad de cada nivel de demanda).<br>
                    <strong>Wald (maximin)</strong> = para cada estación se toma su peor pago y se elige la estación cuyo peor pago sea el mayor.
                </p>
            </details>
        </x-card>
    @endif

    @if ($candidatas->isNotEmpty())
        <x-card>
            <div class="tarjeta__titulo"><h2>Estaciones con alerta de reorden (datos reales)</h2></div>
            <table class="io-tabla">
                <thead><tr><th>Estación</th><th>Zona</th><th>Combustibles bajo el mínimo</th><th>Existencia mínima (L)</th></tr></thead>
                <tbody>
                @foreach ($candidatas as $c)
                    <tr><td>{{ $c->NOM_EST }}</td><td>{{ $c->ZON_EST ?: '—' }}</td><td>{{ $c->combustibles_bajos }}</td><td>{{ number_format($c->minimo, 0, ',', '.') }}</td></tr>
                @endforeach
                </tbody>
            </table>
            <p class="io-nota">Las tres primeras se cargan como alternativas en la tabla de arriba.</p>
        </x-card>
    @endif
</x-layouts.app>