<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Recomendación de abastecimiento - Sistema de Abastecimiento de Combustible - La Paz</title>
    <style>
        :root {
            --petroleo: #123B5D;
            --tecnologico: #1976AB;
            --verde: #27AE60;
            --amarillo: #F2C94C;
            --rojo: #D64545;
            --gris-claro: #F4F6F8;
            --gris-oscuro: #263238;
        }
        body { font-family: Arial, sans-serif; background: var(--gris-claro); margin: 0; padding: 0; color: var(--gris-oscuro); }
        header { background: var(--petroleo); color: white; padding: 20px 40px; }
        header h1 { margin: 0; font-size: 22px; }
        .container { padding: 30px 40px; }
        .card { background: white; border-radius: 10px; padding: 20px 25px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { margin: 0 0 16px; color: var(--petroleo); }
        .barra { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        a.volver { color: var(--tecnologico); text-decoration: none; }
        .resumen { display: flex; gap: 16px; flex-wrap: wrap; margin-bottom: 20px; }
        .caja { background: white; border-radius: 10px; padding: 14px 22px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); min-width: 150px; }
        .caja strong { display: block; font-size: 28px; }
        .caja.rojo strong { color: var(--rojo); }
        .caja.amarillo strong { color: #b58a00; }
        .caja.azul strong { color: var(--tecnologico); }
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--petroleo); color: white; text-align: left; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #e3e8ec; }
        td.num { text-align: right; }
        .badge { padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; color: white; }
        .badge.verde { background: var(--verde); }
        .badge.amarillo { background: var(--amarillo); color: var(--gris-oscuro); }
        .badge.rojo { background: var(--rojo); }
        .badge.azul { background: var(--tecnologico); }
        .nota { color: #607d8b; font-size: 13px; margin-top: 12px; line-height: 1.6; }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Control y Gestión de Abastecimiento de Combustible — Municipio de La Paz</h1>
    </header>

    <div class="container">
        <div class="resumen">
            <div class="caja rojo"><strong>{{ $resumen['Crítica'] }}</strong>Prioridad crítica</div>
            <div class="caja amarillo"><strong>{{ $resumen['Alta'] }}</strong>Prioridad alta</div>
            <div class="caja azul"><strong>{{ $resumen['Media'] }}</strong>Prioridad media</div>
        </div>

        <div class="card">
            <div class="barra">
                <h2>Recomendación de abastecimiento</h2>
                <a class="volver" href="{{ route('dashboard') }}">← Volver al panel</a>
            </div>

            <table>
                <tr>
                    <th>Prioridad</th><th>Estación</th><th>Zona</th><th>Combustible</th>
                    <th>Cantidad (L)</th><th>Nivel</th><th>Sugerido a enviar (L)</th><th>Tránsito (min)</th>
                </tr>
                @forelse ($recomendaciones as $r)
                    <tr>
                        <td><span class="badge {{ $r->color }}">{{ $r->prioridad }}</span></td>
                        <td>{{ $r->estacion }}</td>
                        <td>{{ $r->zona }}</td>
                        <td>{{ $r->combustible }}</td>
                        <td class="num">{{ number_format($r->cantidad, 2) }}</td>
                        <td class="num">{{ $r->porcentaje !== null ? $r->porcentaje . ' %' : 'Sin máximo' }}</td>
                        <td class="num">{{ $r->prioridad === 'Sin necesidad' ? '—' : number_format($r->sugerida, 2) }}</td>
                        <td class="num">{{ $r->transito }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8">No hay estaciones activas.</td></tr>
                @endforelse
            </table>

            <p class="nota">
                Crítica: sin existencias. Alta: igual o menor al mínimo. Media: hasta 1.5 veces el mínimo
                El sugerido es lo que falta para llegar al máximo de la estación
            </p>
        </div>
    </div>
</body>
</html>