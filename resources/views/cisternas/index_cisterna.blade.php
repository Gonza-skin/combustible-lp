
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Cisternas — La Paz</title>

    <style>
        * { box-sizing: border-box; }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background: #f4f6f8;
            color: #2c3e50;
        }

        header {
            background: #1F4E5F;
            color: white;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .logo { font-weight: bold; font-size: 18px; }

        .btn {
            display: inline-block;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: .2s;
        }

        .btn-primary { background: #1F4E5F; color: white; }
        .btn-primary:hover { background: #163b48; }
        .btn-light { background: white; color: #1F4E5F; }
        .btn-edit { background: #e8f1f5; color: #1F4E5F; }
        .btn-delete { background: #fdeaea; color: #a52a2a; }

        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 22px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 24px;
        }

        h1 { margin: 0 0 8px; color: #1F4E5F; font-size: 28px; }
        .subtitle { margin: 0; color: #64748b; }

        .card {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            margin-bottom: 22px;
        }

        .alert {
            padding: 14px 16px;
            border-radius: 6px;
            margin-bottom: 18px;
        }

        .success { background: #e7f6ec; color: #216e39; }
        .error { background: #fdeaea; color: #a52a2a; }

        .search {
            width: 100%;
            max-width: 360px;
            padding: 11px 13px;
            border: 1px solid #ccd5dc;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 18px;
        }

        .table-wrap { overflow-x: auto; }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        th {
            background: #edf2f5;
            color: #1F4E5F;
            text-align: left;
            padding: 14px 12px;
            font-size: 13px;
        }

        td {
            padding: 14px 12px;
            border-bottom: 1px solid #e8edf0;
            font-size: 14px;
        }

        tbody tr:hover { background: #f8fafb; }

        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .disponible { background: #e7f6ec; color: #216e39; }
        .asignada { background: #e8f1ff; color: #2457a7; }
        .en_transito { background: #fff2d8; color: #8a5a00; }
        .mantenimiento { background: #fdeaea; color: #a52a2a; }

        .actions { display: flex; gap: 7px; flex-wrap: wrap; }
        .actions form { margin: 0; }

        .empty {
            text-align: center;
            padding: 35px;
            color: #64748b;
        }

        footer {
            text-align: center;
            padding: 24px;
            background: #1F4E5F;
            color: #cfd8dc;
            font-size: 13px;
            margin-top: 40px;
        }

        @media (max-width: 600px) {
            header { padding: 18px 20px; }
            h1 { font-size: 23px; }
            .card { padding: 16px; }
        }
    </style>
</head>

<body>
<header>
    <div class="logo">Sistema de Abastecimiento — La Paz</div>
    <a href="{{ route('dashboard') }}" class="btn btn-light">
        Volver al panel
    </a>
</header>

<main class="container">
    <div class="heading">
        <div>
            <h1>Gestión de Cisternas</h1>
            <p class="subtitle">
                Administración de vehículos cisterna, combustible y conductores.
            </p>
        </div>

        <a href="{{ route('cisternas.create') }}" class="btn btn-primary">
            + Registrar cisterna
        </a>
    </div>

    @if(session('success'))
        <div class="alert success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert error">{{ session('error') }}</div>
    @endif

    <section class="card">
        <input
            type="search"
            id="busqueda"
            class="search"
            placeholder="Buscar por placa, combustible o conductor..."
            aria-label="Buscar cisternas"
        >

        <div class="table-wrap">
            <table id="tablaCisternas">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Placa</th>
                        <th>Capacidad (L)</th>
                        <th>Combustible</th>
                        <th>Conductor</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($cisternas as $cisterna)
                        <tr>
                            <td>{{ $cisterna->COD_CIS }}</td>
                            <td><strong>{{ $cisterna->PLC_CIS }}</strong></td>
                            <td>{{ number_format($cisterna->CAP_CIS, 2, ',', '.') }}</td>
                            <td>{{ $cisterna->DES_COM ?? 'No disponible' }}</td>
                            <td>{{ $cisterna->NOM_CON ?? 'Sin asignar' }}</td>
                            <td>
                                <span class="status {{ $cisterna->ESD_CIS }}">
                                    {{ ucfirst(str_replace('_', ' ', $cisterna->ESD_CIS)) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a
                                        href="{{ route('cisternas.edit', $cisterna->COD_CIS) }}"
                                        class="btn btn-edit"
                                    >Editar</a>

                                    <form
                                        action="{{ route('cisternas.destroy', $cisterna->COD_CIS) }}"
                                        method="POST"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar esta cisterna?');"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn btn-delete">
                                            Eliminar
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty">
                                Todavía no hay cisternas registradas.
                                Utiliza el botón "Registrar cisterna" para comenzar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <p id="sinResultados" class="empty" hidden>
            No se encontraron cisternas con esa búsqueda.
        </p>
    </section>
</main>

<footer>
    © {{ date('Y') }} Sistema de Control y Gestión de Abastecimiento de Combustible
    — Municipio de La Paz
</footer>

<script>
    const busqueda = document.getElementById('busqueda');
    const filas = document.querySelectorAll('#tablaCisternas tbody tr');
    const mensaje = document.getElementById('sinResultados');

    busqueda.addEventListener('input', function () {
        const texto = this.value.toLowerCase().trim();
        let visibles = 0;

        filas.forEach(function (fila) {
            const coincide = fila.textContent.toLowerCase().includes(texto);
            fila.hidden = !coincide;

            if (coincide) visibles++;
        });

        mensaje.hidden = visibles !== 0;
    });
</script>
</body>
</html>
