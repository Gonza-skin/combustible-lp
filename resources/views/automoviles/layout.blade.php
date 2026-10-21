<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') — Automóviles</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; background: #f4f6f8; color: #2c3e50; font-family: Arial, sans-serif; }
        header { padding: 18px 32px; background: #1F4E5F; color: #fff; }
        header h1 { margin: 0; font-size: 21px; }
        .nav { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px; }
        .nav a, .button { display: inline-block; border: 0; border-radius: 6px; padding: 10px 14px; background: #27AE60; color: #fff; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none; }
        .nav a.secondary, .button.secondary { background: #607d8b; }
        .button.danger { background: #c0392b; }
        main { max-width: 1180px; margin: 28px auto; padding: 0 20px; }
        .panel { margin-bottom: 20px; padding: 22px; border-radius: 10px; background: #fff; box-shadow: 0 2px 6px rgba(0,0,0,.08); }
        .panel h2 { margin: 0 0 18px; color: #1F4E5F; }
        .actions { display: flex; flex-wrap: wrap; gap: 8px; align-items: center; }
        .actions form { display: inline; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; text-align: left; }
        th, td { padding: 12px 10px; border-bottom: 1px solid #e5e9ec; vertical-align: top; }
        th { background: #eef3f5; color: #1F4E5F; }
        label { display: block; margin: 14px 0 6px; font-weight: 600; }
        input, select { width: 100%; max-width: 620px; padding: 10px 12px; border: 1px solid #cbd5da; border-radius: 6px; font: inherit; }
        .field-error { margin-top: 4px; color: #c0392b; font-size: 13px; }
        .notice { margin-bottom: 18px; padding: 12px 14px; border-radius: 6px; background: #e8f5e9; color: #236b3b; }
        .notice.error { background: #fdecea; color: #a93226; }
        .details { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .details div { padding: 12px; border-radius: 6px; background: #f4f6f8; }
        .details dt { margin-bottom: 5px; color: #607d8b; font-size: 13px; }
        .details dd { margin: 0; font-weight: 600; overflow-wrap: anywhere; }
        .pagination { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-top: 18px; }
        .muted { color: #607d8b; }
        @media (max-width: 600px) { header { padding: 16px 20px; } main { margin-top: 18px; padding: 0 12px; } .panel { padding: 16px; } }
    </style>
    @include('partials.tema-head')
</head>
<body>
    @include('partials.tema-body')
    @include('partials.dashboard-sidebar')
    @php
        $esAdministrador = \App\Http\Middleware\AutomovilAccess::isAdministrator(auth()->user());
    @endphp
    <header>
        <h1>Sistema de Control y Gestión — Automóviles</h1>
        <nav class="nav" aria-label="Navegación de automóviles">
            <a class="secondary" href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('automoviles.index') }}">Automóviles</a>
            @if ($esAdministrador)
                <a href="{{ route('automoviles.create') }}">Registrar automóvil</a>
            @endif
        </nav>
    </header>
    <main>
        @if (session('success'))
            <div class="notice" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->has('automovil'))
            <div class="notice error" role="alert">{{ $errors->first('automovil') }}</div>
        @endif
        @yield('content')
    </main>
</body>
</html>
