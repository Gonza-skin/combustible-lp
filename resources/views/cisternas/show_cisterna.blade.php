
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de Cisterna — La Paz</title>

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
            padding: 11px 17px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-primary { background: #1F4E5F; color: white; }
        .btn-secondary { background: #e8edf0; color: #2c3e50; }

        .container {
            max-width: 850px;
            margin: 38px auto;
            padding: 0 22px;
        }

        h1 { color: #1F4E5F; margin-bottom: 8px; }
        .subtitle { color: #64748b; margin-bottom: 25px; }

        .card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 22px;
        }

        .item {
            padding: 16px;
            background: #f8fafb;
            border: 1px solid #e8edf0;
            border-radius: 7px;
        }

        .item dt {
            color: #64748b;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .item dd {
            margin: 0;
            color: #1F4E5F;
            font-weight: 600;
            font-size: 16px;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 12px;
            background: #e8f1f5;
        }

        .buttons {
            display: flex;
            gap: 12px;
            margin-top: 26px;
            flex-wrap: wrap;
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
            .details { grid-template-columns: 1fr; }
            .card { padding: 20px; }
        }
    </style>
</head>

<body>
<header>
    <div class="logo">Sistema de Abastecimiento — La Paz</div>
    <a href="{{ route('dashboard') }}"
       class="btn"
       style="background:white;color:#1F4E5F">
        Volver al panel
    </a>
</header>

<main class="container">
    <h1>Detalle de cisterna</h1>
    <p class="subtitle">
        Información registrada del vehículo cisterna.
    </p>

    <section class="card">
        <dl class="details">
            <div class="item">
                <dt>Código de cisterna</dt>
                <dd>{{ $cisterna->COD_CIS }}</dd>
            </div>

            <div class="item">
                <dt>Placa</dt>
                <dd>{{ $cisterna->PLC_CIS }}</dd>
            </div>

            <div class="item">
                <dt>Capacidad</dt>
                <dd>{{ number_format($cisterna->CAP_CIS, 2, ',', '.') }} litros</dd>
            </div>

            <div class="item">
                <dt>Tipo de combustible</dt>
                <dd>{{ $cisterna->DES_COM ?? 'No disponible' }}</dd>
            </div>

            <div class="item">
                <dt>Conductor asignado</dt>
                <dd>{{ $cisterna->NOM_CON ?? 'Sin asignar' }}</dd>
            </div>

            <div class="item">
                <dt>Estado actual</dt>
                <dd>
                    <span class="status">
                        {{ ucfirst(str_replace('_', ' ', $cisterna->ESD_CIS)) }}
                    </span>
                </dd>
            </div>
        </dl>

        <div class="buttons">
            <a
                href="{{ route('cisternas.edit', $cisterna->COD_CIS) }}"
                class="btn btn-primary"
            >
                Editar cisterna
            </a>

            <a href="{{ route('cisternas.index') }}"
               class="btn btn-secondary">
                Volver al listado
            </a>
        </div>
    </section>
</main>

<footer>
    © {{ date('Y') }} Sistema de Control y Gestión de Abastecimiento de Combustible
    — Municipio de La Paz
</footer>
</body>
</html>
