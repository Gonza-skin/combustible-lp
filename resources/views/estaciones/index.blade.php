<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Estaciones - Sistema de Abastecimiento de Combustible - La Paz</title>
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
        .card {
            background: white; border-radius: 10px; padding: 20px 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }
        h2 { margin: 0 0 16px; color: var(--petroleo); }
        .barra { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
        a.volver { color: var(--tecnologico); text-decoration: none; }
        .btn {
            display: inline-block; background: var(--tecnologico); color: white; border: 0;
            border-radius: 6px; padding: 8px 14px; cursor: pointer; text-decoration: none; font-size: 14px;
        }
        .btn:hover { opacity: .9; }
        .btn-rojo { background: var(--rojo); }
        .btn-claro { background: var(--gris-claro); color: var(--gris-oscuro); border: 1px solid #cfd8dc; }
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--petroleo); color: white; text-align: left; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #e3e8ec; }
        .badge { padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; color: white; }
        .badge.verde { background: var(--verde); }
        .badge.amarillo { background: var(--amarillo); color: var(--gris-oscuro); }
        .badge.rojo { background: var(--rojo); }
        .aviso-ok { background: #e6f6ec; border-left: 4px solid var(--verde); padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; }
        .aviso-error { background: #fbeaea; border-left: 4px solid var(--rojo); padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Control y Gestión de Abastecimiento de Combustible — Municipio de La Paz</h1>
    </header>

    <div class="container">
        @if (session('ok'))<div class="aviso-ok">{{ session('ok') }}</div>@endif
        @if (session('error'))<div class="aviso-error">{{ session('error') }}</div>@endif

        <div class="card">
            <div class="barra">
                <h2>Estaciones</h2>
                <div>
                    <a class="volver" href="{{ route('dashboard') }}">← Volver al panel</a>
                    &nbsp;&nbsp;
                    <a class="btn" href="{{ route('estaciones.create') }}">+ Nueva estación</a>
                </div>
            </div>

            @php
                $colores = ['normal' => 'verde', 'nivel_bajo' => 'amarillo', 'sin_combustible' => 'rojo'];
                $textos = ['normal' => 'Normal', 'nivel_bajo' => 'Nivel bajo', 'sin_combustible' => 'Sin combustible'];
            @endphp

            <table>
                <tr>
                    <th>Nombre</th><th>Zona</th><th>Dirección</th>
                    <th>Mín</th><th>Máx</th><th>Estado</th><th>Activa</th><th>Acciones</th>
                </tr>
                @forelse ($estaciones as $e)
                    <tr>
                        <td>{{ $e->NOM_EST }}</td>
                        <td>{{ $e->ZON_EST }}</td>
                        <td>{{ $e->DIR_EST }}</td>
                        <td>{{ $e->MIN_EST }}</td>
                        <td>{{ $e->MAX_EST }}</td>
                        <td><span class="badge {{ $colores[$e->ESD_EST] ?? '' }}">{{ $textos[$e->ESD_EST] ?? $e->ESD_EST }}</span></td>
                        <td>{{ $e->ACT_EST ? 'Sí' : 'No' }}</td>
                        <td>
                            <a class="btn btn-claro" href="{{ route('estaciones.edit', $e) }}">Editar</a>
                            <form action="{{ route('estaciones.destroy', $e) }}" method="POST" style="display:inline"
                                  onsubmit="return confirm('¿Eliminar esta estación?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-rojo">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8">No hay estaciones registradas.</td></tr>
                @endforelse
            </table>
        </div>
    </div>
</body>
</html>