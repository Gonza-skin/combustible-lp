<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Movimientos - Sistema de Abastecimiento de Combustible - La Paz</title>
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
        .filtros { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; margin-bottom: 18px; }
        .filtros label { display: block; font-weight: bold; font-size: 13px; margin-bottom: 4px; }
        .filtros select { padding: 8px; border: 1px solid #b0bec5; border-radius: 6px; min-width: 170px; }
        .btn { display: inline-block; background: var(--tecnologico); color: white; border: 0; border-radius: 6px; padding: 9px 16px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-claro { background: var(--gris-claro); color: var(--gris-oscuro); border: 1px solid #cfd8dc; }
        table { width: 100%; border-collapse: collapse; }
        th { background: var(--petroleo); color: white; text-align: left; padding: 10px; }
        td { padding: 10px; border-bottom: 1px solid #e3e8ec; }
        td.num { text-align: right; }
        .badge { padding: 3px 10px; border-radius: 999px; font-size: 12px; font-weight: bold; color: white; }
        .badge.entrada { background: var(--verde); }
        .badge.salida { background: var(--tecnologico); }
        .nota { color: #607d8b; font-size: 13px; margin-top: 12px; }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Control y Gestión de Abastecimiento de Combustible — Municipio de La Paz</h1>
    </header>

    <div class="container">
        <div class="card">
            <div class="barra">
                <h2>Movimientos de existencias</h2>
                <div>
                    <a class="volver" href="{{ route('existencias.index') }}">← Existencias</a>
                    &nbsp;&nbsp;
                    <a class="volver" href="{{ route('dashboard') }}">Volver al panel</a>
                </div>
            </div>

            <form method="GET" action="{{ route('movimientos.index') }}" class="filtros">
                <div>
                    <label>Estación</label>
                    <select name="estacion">
                        <option value="">Todas</option>
                        @foreach ($estaciones as $e)
                            <option value="{{ $e->COD_EST }}" @selected(($filtros['estacion'] ?? null) == $e->COD_EST)>{{ $e->NOM_EST }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Combustible</label>
                    <select name="combustible">
                        <option value="">Todos</option>
                        @foreach ($combustibles as $c)
                            <option value="{{ $c->COD_COM }}" @selected(($filtros['combustible'] ?? null) == $c->COD_COM)>{{ $c->DES_COM }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label>Tipo</label>
                    <select name="tipo">
                        <option value="">Todos</option>
                        <option value="entrada" @selected(($filtros['tipo'] ?? null) === 'entrada')>Entrada</option>
                        <option value="salida" @selected(($filtros['tipo'] ?? null) === 'salida')>Salida</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="btn">Filtrar</button>
                    <a href="{{ route('movimientos.index') }}" class="btn btn-claro">Limpiar</a>
                </div>
            </form>

            @php
                $roles = [
                    'administrador'          => 'Administrador',
                    'administrador_sistema'  => 'Administrador del sistema',
                    'administrador_deposito' => 'Administrador de depósito',
                    'sub_administrador'      => 'Subadministrador',
                    'encargado_estacion'     => 'Encargado de estación',
                    'conductor_cisterna'     => 'Conductor de cisterna',
                    'supervisor'             => 'Supervisor',
                    'ciudadano'              => 'Ciudadano',
                ];
            @endphp

            <table>
                <tr>
                    <th>Fecha</th><th>Estación</th><th>Combustible</th>
                    <th>Tipo</th><th>Cantidad (L)</th><th>Referencia</th><th>Usuario</th>
                </tr>
                @forelse ($movimientos as $m)
                    <tr>
                        <td>{{ $m->FEC_MOV }}</td>
                        <td>{{ $m->NOM_EST }}</td>
                        <td>{{ $m->DES_COM }}</td>
                        <td><span class="badge {{ $m->TIP_MOV }}">{{ ucfirst($m->TIP_MOV) }}</span></td>
                        <td class="num">{{ number_format($m->CAN_MOV, 2) }}</td>
                        <td>{{ $m->REF_MOV }}</td>
                        <td>{{ $roles[$m->DES_ROL] ?? ($m->DES_ROL ? ucfirst(str_replace('_', ' ', $m->DES_ROL)) : 'Sin usuario') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7">No hay movimientos con esos filtros.</td></tr>
                @endforelse
            </table>

            <p class="nota">Se muestran los 200 movimientos más recientes.</p>
        </div>
    </div>
</body>
</html>