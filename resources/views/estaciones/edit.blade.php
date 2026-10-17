<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar estación - Sistema de Abastecimiento de Combustible - La Paz</title>
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
        .card { background: white; border-radius: 10px; padding: 20px 25px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); max-width: 600px; }
        h2 { margin: 0 0 16px; color: var(--petroleo); }
        .campo { margin-bottom: 14px; }
        .campo label { display: block; font-weight: bold; margin-bottom: 4px; }
        .campo input[type=text], .campo input[type=number], .campo select {
            width: 100%; padding: 8px; border: 1px solid #b0bec5; border-radius: 6px; box-sizing: border-box;
        }
        .campo input:focus, .campo select:focus { outline: 2px solid var(--tecnologico); }
        .btn { display: inline-block; background: var(--tecnologico); color: white; border: 0; border-radius: 6px; padding: 9px 16px; cursor: pointer; text-decoration: none; font-size: 14px; }
        .btn-claro { background: var(--gris-claro); color: var(--gris-oscuro); border: 1px solid #cfd8dc; }
        .aviso-error { background: #fbeaea; border-left: 4px solid var(--rojo); padding: 10px 14px; border-radius: 6px; margin-bottom: 14px; }
        input[type=number]::-webkit-outer-spin-button,
        input[type=number]::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
        input[type=number] { -moz-appearance: textfield; appearance: textfield; }
    </style>
</head>
<body>
    <header>
        <h1>Sistema de Control y Gestión de Abastecimiento de Combustible — Municipio de La Paz</h1>
    </header>

    <div class="container">
        <div class="card">
            <h2>Editar estación</h2>
            <form action="{{ route('estaciones.update', $estacion) }}" method="POST">
                @method('PUT')
                @include('estaciones._form')
                <button type="submit" class="btn">Actualizar</button>
                <a href="{{ route('estaciones.index') }}" class="btn btn-claro">Cancelar</a>
            </form>
        </div>
    </div>
</body>
</html>