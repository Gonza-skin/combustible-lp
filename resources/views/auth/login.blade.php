<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Iniciar sesión — Sistema de Abastecimiento La Paz</title>
    <style>
        :root {
            --petroleo: #123B5D;
            --verde: #27AE60;
            --fondo: #f4f6f8;
            --texto: #1f2d3a;
            --error: #c0392b;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: var(--petroleo);
            color: var(--texto);
            padding: 16px;
        }
        .tarjeta {
            width: 100%;
            max-width: 400px;
            background: #fff;
            border-radius: 14px;
            padding: 32px 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .25);
        }
        h1 { margin: 0 0 4px; font-size: 1.4rem; color: var(--petroleo); }
        .sub { margin: 0 0 24px; font-size: .9rem; color: #5b6b79; }
        label { display: block; margin-bottom: 6px; font-size: .85rem; font-weight: 600; }
        input {
            width: 100%;
            padding: 11px 12px;
            margin-bottom: 16px;
            border: 1px solid #cfd8df;
            border-radius: 8px;
            font-size: 1rem;
        }
        input:focus { outline: 2px solid var(--verde); border-color: var(--verde); }
        button {
            width: 100%;
            padding: 12px;
            border: 0;
            border-radius: 8px;
            background: var(--petroleo);
            color: #fff;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
        }
        button:hover { background: var(--verde); }
        .error { margin: -8px 0 14px; font-size: .85rem; color: var(--error); }
        .volver { display: block; margin-top: 18px; text-align: center; font-size: .85rem; color: var(--petroleo); }
    </style>
</head>
<body>
    <main class="tarjeta">
        <h1>Iniciar sesión</h1>
        <p class="sub">Sistema de Abastecimiento - La Paz</p>

        <form method="POST" action="{{ url('/login') }}">
            @csrf

            <label for="email">Correo electronico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="error">{{ $message }}</div>
            @enderror

            <label for="password">Contraseña</label>
            <input id="password" type="password" name="password" required>

            <button type="submit">Entrar</button>
        </form>
        <a class="volver" href="{{ route('registro') }}">¿No tienes cuenta? Registrate</a>
        <a class="volver" href="{{ route('home') }}">Volver al inicio</a>
    </main>
</body>
</html>