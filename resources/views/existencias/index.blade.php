<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Existencias - Sistema de Abastecimiento de Combustible - La Paz</title>
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
        .btn { display: inline-block; background: var(--tecnologico); color: white; border: 0; border-radius: 6px; padding: 8px 14px; cursor: pointer; text-decoration: none; font-size: 14px; }
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--petroleo); color: white; text-align: left; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #e3e8ec; }
        td.num { text-align: right; }
        .badge { padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; color: white; }
        .badge.verde { background: var(--verde); }
        .badge.amarillo { background: var(--amarillo); color: var(--gris-oscuro); }
        .badge.rojo { background: var(--rojo); }
        .aviso-ok { background: #e6f6ec; border-left: 4px solid var(--verde); padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Control y Gestión de Abastecimiento de Combustible — Municipio de La Paz</h1>
    </header>

    <div class="container">
        @if (session('ok'))<div class="aviso-ok">{{ session('ok') }}</div>@endif

        <div class="card">
            <div class="barra">
                <h2>Existencias por estación</h2>
                <a class="volver" href="{{ route('dashboard') }}">← Volver al panel</a>
            </div>

            @php
                $colores = ['normal' => 'verde', 'nivel_bajo' => 'amarillo', 'sin_combustible' => 'rojo'];
                $textos = ['normal' => 'Normal', 'nivel_bajo' => 'Nivel bajo', 'sin_combustible' => 'Sin combustible'];
            @endphp

            <table>
                <tr>
                    <th>Estación</th><th>Combustible</th><th>Cantidad (L)</th>
                    <th>Mínimo</th><th>Máximo</th><th>Estado</th><th>Última actualización</th><th>Acción</th>
                </tr>
                @forelse ($filas as $f)
                    <tr>
                        <td>{{ $f->NOM_EST }}</td>
                        <td>{{ $f->DES_COM }}</td>
                        <td class="num">{{ number_format($f->CAN_EXI, 2) }}</td>
                        <td class="num">{{ number_format($f->MIN_EST, 2) }}</td>
                        <td class="num">{{ number_format($f->MAX_EST, 2) }}</td>
                        <td><span class="badge {{ $colores[$f->ESD_EST] ?? '' }}">{{ $textos[$f->ESD_EST] ?? $f->ESD_EST }}</span></td>
                        <td>{{ $f->FEC_EXI ?? 'Sin registro' }}</td>
                        <td><a class="btn" href="{{ route('existencias.ajustar', [$f->COD_EST, $f->COD_COM]) }}">Ajustar</a></td>
                    </tr>
                @empty
                    <tr><td colspan="8">No hay estaciones activas. Crea una primero en Estaciones.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
</body>
</html>