<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Abastecimiento de Combustible — Municipio de La Paz</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            background: #f4f6f8;
            color: #2c3e50;
        }

        /* Barra superior */
        header {
            background: #123B5D;
            color: white;
            padding: 18px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        header .logo {
            font-weight: bold;
            font-size: 18px;
        }
        header .btn-login {
            background: #ffffff;
            color: #1F4E5F;
            padding: 10px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.2s;
        }
        header .btn-login:hover {
            background: #e0e0e0;
        }
        .acciones-header { display: flex; gap: 10px; align-items: center; }
        header .btn-registro {
            background: #27AE60;
            color: #0b2740;
            padding: 10px 22px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
        }
        header .btn-registro:hover { background: #1f9150; color: #fff; }

        /* INFORMFACION IMPORTANTE...*/
        .hero {
            background: #123B5D;
            color: white;
            text-align: center;
            padding: 70px 20px;
        }
        .hero h1 {
            font-size: 32px;
            margin-bottom: 15px;
        }
        .hero p {
            max-width: 700px;
            margin: 0 auto;
            font-size: 17px;
            line-height: 1.6;
            opacity: 0.95;
        }

        /* Sección de características */
        .features {
            max-width: 1100px;
            margin: 0 auto;
            padding: 60px 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
        }
        .feature-card {
            background: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        }
        .feature-card .icon {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .feature-card h3 {
            margin: 0 0 10px;
            color: #123B5D;
        }
        .feature-card p {
            margin: 0;
            font-size: 14px;
            color: #555;
            line-height: 1.5;
        }

        /* Sección informativa */
        .info {
            background: white;
            padding: 50px 30px;
            text-align: center;
        }
        .info h2 {
            color: #123B5D;
        }
        .info p {
            max-width: 750px;
            margin: 15px auto 0;
            line-height: 1.6;
            color: #555;
        }
        /* parte final */
        footer {
            text-align: center;
            padding: 25px;
            background: #123B5D;
            color: #cfd8dc;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">Sistema de Abastecimiento — La Paz</div>
        <div class="acciones-header">
    <a href="{{ route('login') }}" class="btn-login">Iniciar sesión</a>
    <a href="{{ route('registro') }}" class="btn-registro">Registrarse</a>
</div>
    </header>

    <section class="hero">
        <h1>Información confiable sobre combustible en tu estación más cercana</h1>
        <p>
            Consulta la disponibilidad de gasolina y diésel, el estado de las filas
            y la ubicación de las estaciones de servicio en el Municipio de La Paz,
            en tiempo real.
        </p>
    </section>

    <section class="features">
        <div class="feature-card">
            <div class="icon">.</div>
            <h3>Mapa interactivo</h3>
            <p>Ubica las estaciones de servicio más cercanas y su estado actual de abastecimiento.</p>
        </div>
        <div class="feature-card">
            <div class="icon">.</div>
            <h3>Disponibilidad en tiempo real</h3>
            <p>Consulta si una estación tiene combustible disponible, nivel bajo, o está agotada.</p>
        </div>
        <div class="feature-card">
            <div class="icon">.</div>
            <h3>Reportes ciudadanos</h3>
            <p>Comparte y consulta el estado de las filas reportado por otros usuarios en vivo.</p>
        </div>
        <div class="feature-card">
            <div class="icon">.</div>
            <h3>Seguimiento de abastecimiento</h3>
            <p>El municipio controla la asignación de cisternas, horarios y entregas a cada estación.</p>
        </div>
    </section>

    <section class="info">
        <h2>¿Que es este sistema?</h2>
        <p>
            El Sistema de Control y Gestión de Abastecimiento de Combustible nace para
            enfrentar la crisis de desabastecimiento en el Municipio de La Paz, brindando
            información digital, confiable y en tiempo real sobre la disponibilidad de
            combustible en las estaciones de servicio, reduciendo la incertidumbre de miles
            de conductores que dependen de este recurso a diario.
        </p>
    </section>

    <footer>
        © {{ date('Y') }} Sistema de Control y Gestión de Abastecimiento de Combustible — Municipio de La Paz
    </footer>
</body>
</html>