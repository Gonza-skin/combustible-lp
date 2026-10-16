<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Crear cuenta — Sistema de Abastecimiento La Paz</title>
    <style>
        :root { --petroleo: #123B5D; --verde: #27AE60; --texto: #1f2d3a; }
        * { box-sizing: border-box; }
        body {
            margin: 0; min-height: 100vh; display: flex; align-items: center; justify-content: center;
            font-family: system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: var(--petroleo); color: var(--texto); padding: 16px;
        }
        .tarjeta { width: 100%; max-width: 420px; background: #fff; border-radius: 14px; padding: 32px 28px; box-shadow: 0 10px 30px rgba(0,0,0,.25); }
        h1 { margin: 0 0 4px; font-size: 1.4rem; color: var(--petroleo); }
        .sub { margin: 0 0 24px; font-size: .9rem; color: #5b6b79; }
        label { display: block; margin-bottom: 6px; font-size: .85rem; font-weight: 600; }
        input { width: 100%; padding: 11px 12px; margin-bottom: 16px; border: 1px solid #cfd8df; border-radius: 8px; font-size: 1rem; }
        input:focus { outline: 2px solid var(--verde); border-color: var(--verde); }
        button { width: 100%; padding: 12px; border: 0; border-radius: 8px; background: var(--verde); color: #0b2740; font-size: 1rem; font-weight: 700; cursor: pointer; }
        button:hover { background: #1f9150; color: #fff; }
        .error { margin: -8px 0 14px; font-size: .85rem; font-weight: 700; color: var(--petroleo); }
        .error::before { content: '! '; }
        .volver { display: block; margin-top: 18px; text-align: center; font-size: .85rem; color: var(--petroleo); }
    </style>
</head>
<body>
    <main class="tarjeta">
        <h1>Crear cuenta</h1>
        <p class="sub">Regístrate para consultar estaciones y disponibilidad.</p>

        <form method="POST" action="{{ route('registro') }}">
            @csrf

            <label for="nombre">Nombre completo</label>
            <input id="nombre" type="text" name="nombre" value="{{ old('nombre') }}" required autofocus>
            @error('nombre') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Correo electrónico</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Contraseña (mínimo 8 caracteres)</label>
            <input id="password" type="password" name="password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <label for="password_confirmation">Repite la contraseña</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required>

            <button type="submit">Crear cuenta</button>
        </form>

        <a class="volver" href="{{ route('login') }}">¿Ya tienes cuenta? Inicia sesión</a>
        <a class="volver" href="{{ route('home') }}">← Volver al inicio</a>
    </main>
</body>
</html>