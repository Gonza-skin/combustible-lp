<x-layouts.app title="Predicción de disponibilidad">
    <style>
        .io-tabla{width:100%;border-collapse:collapse}
        .io-tabla th,.io-tabla td{padding:9px 10px;border-bottom:1px solid var(--borde,#ddd);text-align:left;vertical-align:middle}
        .io-tabla th{font-size:12px;text-transform:uppercase;letter-spacing:.06em;color:var(--texto-suave,#667)}
        .io-bar{height:8px;border-radius:99px;background:var(--borde,#ddd);overflow:hidden;margin-top:5px;min-width:90px}
        .io-bar i{display:block;height:100%;border-radius:99px;background:var(--ac,#19d98b);box-shadow:0 0 10px var(--glow,transparent)}
        .io-bar.mal i{background:var(--rojo,#d64545);box-shadow:none}
        .io-nota{color:var(--texto-suave,#667);font-size:13px;line-height:1.6}
        .io-filtros{display:flex;gap:12px;flex-wrap:wrap;align-items:flex-end}
        .io-filtros label{display:block;font-size:12px;color:var(--texto-suave,#667);margin-bottom:4px}
        .io-filtros input,.io-filtros select{padding:8px 10px;border:1px solid var(--borde,#ccc);border-radius:9px;background:var(--campo-fondo,#fff);color:inherit}
        .io-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(360px,1fr));gap:20px}
        .io-grid>div{min-width:0}
        .io-mat-wrap{overflow-x:auto}
        .io-mat td,.io-mat th{text-align:center;padding:8px 12px;border:1px solid var(--borde,#ddd)}
        .io-mat td{font-weight:600}
        .io-pill{display:inline-block;padding:2px 10px;border-radius:99px;font-size:12px;border:1px solid var(--borde,#ccc)}
        .io-pill.mal{color:var(--rojo,#d64545);border-color:var(--rojo,#d64545)}
        details.io-det summary{cursor:pointer;color:var(--ac2,#19d98b);font-weight:600}
    </style>
    @php
        $pct = fn ($x) => number_format($x * 100, 1, ',', '.').' %';
    @endphp

    <x-page-header titulo="Predicción de disponibilidad"
                   subtitulo="Cadenas de Markov: probabilidad de que una estación se quede sin combustible" />

    <div class="stats">
        <x-stat :valor="$enAlerta" texto="Estaciones sobre el umbral de riesgo" :alerta="$enAlerta > 0" />
        <x-stat :valor="$filas->count()" texto="Estaciones analizadas" />
        <x-stat :valor="$transicionesTotales" texto="Transiciones en el historial" />
    </div>

    <x-card>
        <form method="GET" action="{{ route('markov.index') }}" class="io-filtros">
            <div><label>Estación a detallar</label>
                <select name="estacion">
                    @foreach ($estaciones as $e)
                        <option value="{{ $e->COD_EST }}" @selected($detalle && (int) $detalle['estacion']->COD_EST === (int) $e->COD_EST)>{{ $e->NOM_EST }}</option>
                    @endforeach
                </select></div>
            <div><label>Días a predecir</label><input type="number" name="dias" min="1" max="60" value="{{ $dias }}"></div>
            <div><label>Umbral de alerta (%)</label><input type="number" name="umbral" min="1" max="100" step="any" value="{{ $umbral }}"></div>
            <div><label>Datos</label>
                <select name="fuente">
                    <option value="real" @selected($fuente === 'real')>Historial real de reportes</option>
                    <option value="informe" @selected($fuente === 'informe')>Ejemplo del informe (datos ficticios)</option>
                </select></div>
            <x-button type="submit">Calcular</x-button>
        </form>
        @if ($fuente === 'informe')
            <p class="io-nota">Modo informe: todas las estaciones usan la matriz de la Tabla 2 y parten de «Disponible». Con 3 días debe dar 17,3 % de agotamiento.</p>
        @endif
    </x-card>

    <x-card>
        <div class="tarjeta__titulo"><h2>Ranking de riesgo de agotamiento a {{ $dias }} {{ $dias === 1 ? 'día' : 'días' }}</h2></div>
        @if ($filas->isEmpty())
            <p class="io-nota">No hay estaciones activas.</p>
        @else
        <div style="overflow-x:auto">
        <table class="io-tabla">
            <thead><tr><th>#</th><th>Estación</th><th>Hoy</th><th>Prob. de agotarse</th><th>Días hasta agotarse</th><th>Agotada al mes</th><th>Matriz</th><th></th></tr></thead>
            <tbody>
            @foreach ($filas as $n => $f)
                <tr>
                    <td>{{ $n + 1 }}</td>
                    <td><a href="{{ route('markov.index', ['estacion' => $f['estacion']->COD_EST, 'dias' => $dias, 'umbral' => $umbral, 'fuente' => $fuente]) }}">{{ $f['estacion']->NOM_EST }}</a>
                        <div class="io-nota">{{ $f['estacion']->ZON_EST }}</div></td>
                    <td>{{ $estados[$f['actual']] }}</td>
                    <td>{{ $pct($f['riesgo']) }}<div class="io-bar {{ $f['alerta'] ? 'mal' : '' }}"><i style="width:{{ round($f['riesgo'] * 100) }}%"></i></div></td>
                    <td>{{ is_null($f['dias_hasta']) ? '—' : number_format($f['dias_hasta'], 1, ',', '.') }}</td>
                    <td>{{ is_null($f['agotada_mes']) ? '—' : number_format($f['agotada_mes'], 1, ',', '.').' días' }}</td>
                    <td><span class="io-pill">{{ ['estacion' => 'propia', 'global' => 'global', 'ejemplo' => 'ejemplo'][$f['origen']] }}</span></td>
                    <td>@if ($f['alerta'])<span class="io-pill mal">ALERTA</span>@endif</td>
                </tr>
            @endforeach
            </tbody>
        </table>
        </div>
        <p class="io-nota">«Matriz propia» = armada con el historial de esa estación; «global» = historial de todas (la estación tiene pocos datos); «ejemplo» = matriz del informe (aún no hay historial).</p>
        @endif
    </x-card>

    @if ($detalle)
        @php
            $d = $detalle;
        @endphp
        <x-card>
            <div class="tarjeta__titulo"><h2>Detalle: {{ $d['estacion']->NOM_EST }}</h2>
                <span class="io-nota">Hoy: <strong>{{ $estados[$d['actual']] }}</strong>@if ($d['ultimo']) (último reporte {{ $d['ultimo']['fecha'] }})@endif</span></div>
            <div class="io-grid">
                <div>
                    <strong>Matriz de transición P</strong>
                    <div class="io-mat-wrap">
                    <table class="io-mat" style="border-collapse:collapse;margin-top:8px">
                        <tr><th>Hoy ↓ / Mañana →</th>@foreach ($estados as $e)<th>{{ $e }}</th>@endforeach</tr>
                        @foreach ($d['matriz'] as $i => $fila)
                            <tr><th>{{ $estados[$i] }}</th>
                            @foreach ($fila as $j => $v)
                                <td style="background:color-mix(in srgb,var(--ac,#19d98b) {{ round($v * 60) }}%,transparent)">{{ number_format($v, 2, ',', '.') }}</td>
                            @endforeach</tr>
                        @endforeach
                    </table>
                    </div>
                    @if ($d['conteos'])<p class="io-nota">Armada con {{ $d['transiciones'] }} transiciones observadas (p<sub>ij</sub> = n<sub>ij</sub> / n<sub>i</sub>).</p>@endif
                </div>
                <div>
                    <strong>Estado dentro de {{ $dias }} {{ $dias === 1 ? 'día' : 'días' }}</strong>
                    @foreach ($estados as $i => $e)
                        <div style="margin-top:8px">{{ $e }}: {{ $pct($d['an'][$i]) }}<div class="io-bar {{ $i === 2 ? 'mal' : '' }}"><i style="width:{{ round($d['an'][$i] * 100) }}%"></i></div></div>
                    @endforeach
                </div>
                <div>
                    <strong>Largo plazo (π)</strong>
                    @if ($d['pi'])
                        @foreach ($estados as $i => $e)
                            <div style="margin-top:8px">{{ $e }}: {{ $pct($d['pi'][$i]) }}<div class="io-bar {{ $i === 2 ? 'mal' : '' }}"><i style="width:{{ round($d['pi'][$i] * 100) }}%"></i></div></div>
                        @endforeach
                        <p class="io-nota">Agotada ≈ {{ number_format($d['agotada_mes'], 1, ',', '.') }} días por mes.</p>
                    @else
                        <p class="io-nota">Sin distribución estable con estos datos.</p>
                    @endif
                    @if ($d['mu'])
                        <p class="io-nota">Días hasta el primer agotamiento: desde Disponible {{ number_format($d['mu'][0], 2, ',', '.') }}; desde Fila larga {{ number_format($d['mu'][1], 2, ',', '.') }}.</p>
                    @endif
                </div>
            </div>
            <details class="io-det" style="margin-top:14px">
                <summary>¿Cómo se calculó?</summary>
                <p class="io-nota">
                    1) Cada día se toma el peor estado reportado (Disponible &lt; Fila larga &lt; Agotado) y se cuentan los pasos entre días consecutivos: p<sub>ij</sub> = n<sub>ij</sub> / n<sub>i</sub>.<br>
                    2) Probabilidad a n días: a<sub>n</sub> = a<sub>0</sub> · Pⁿ, con a<sub>0</sub> = el estado de hoy.<br>
                    3) Largo plazo: π · P = π con Σπ = 1 (sistema lineal resuelto por Gauss).<br>
                    4) Días hasta el primer agotamiento: μ = (I − N)⁻¹ · 1, donde N es P sin la fila y la columna de «Agotado».<br>
                    5) Alerta si la probabilidad de agotarse en n días supera el umbral configurado.
                </p>
            </details>
        </x-card>
    @endif
</x-layouts.app>