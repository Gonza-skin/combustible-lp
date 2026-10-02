<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Sistema de Abastecimiento de Combustible - La Paz</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; margin: 0; padding: 0; }
        header { background: #1F4E5F; color: white; padding: 20px 40px; }
        header h1 { margin: 0; font-size: 22px; }
        .container { padding: 30px 40px; }
        .cards { display: flex; gap: 20px; flex-wrap: wrap; }
        .card {
            background: white; border-radius: 10px; padding: 20px 25px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1); flex: 1; min-width: 200px;
        }
        .card h2 { margin: 0; font-size: 32px; color: #1F4E5F; }
        .card p { margin: 5px 0 0; color: #666; }
        .card.alerta h2 { color: #c0392b; }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Control y Gestión de Abastecimiento de Combustible — Municipio de La Paz</h1>
    </header>

    <div class="container">
        <div class="cards">
            <div class="card">
                <h2>{{ $estacionesActivas }}</h2>
                <p>Estaciones activas</p>
            </div>
            <div class="card alerta">
                <h2>{{ $estacionesNivelBajo }}</h2>
                <p>Estaciones con nivel bajo</p>
            </div>
            <div class="card">
                <h2>{{ $cisternasEnTransito }}</h2>
                <p>Cisternas en tránsito</p>
            </div>
            <div class="card alerta">
                <h2>{{ $incidenciasAbiertas }}</h2>
                <p>Incidencias abiertas</p>
            </div>
        </div>
    </div>
</body>
</html>