<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('titulo', 'Abastecimiento') - SCAC La Paz</title>
<style>
*{box-sizing:border-box}
body{margin:0;font-family:Arial,Helvetica,sans-serif;background:#f3f4f6;color:#111827}
header{background:#1e3a8a;color:#fff;padding:12px 24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px}
header a{color:#fff;text-decoration:none;margin-right:16px;font-size:14px}
header a:hover{text-decoration:underline}
header form{display:inline}
header .salir{background:transparent;border:1px solid #fff;color:#fff;padding:4px 10px;border-radius:4px;cursor:pointer}
main{max-width:1000px;margin:24px auto;padding:0 16px}
.card{background:#fff;border-radius:8px;padding:20px;box-shadow:0 1px 3px rgba(0,0,0,.1)}
h1{margin:0 0 16px;font-size:22px}
table{width:100%;border-collapse:collapse;font-size:14px}
th,td{text-align:left;padding:8px;border-bottom:1px solid #e5e7eb}
th{background:#f9fafb}
.btn{display:inline-block;background:#1e3a8a;color:#fff;border:0;padding:7px 14px;border-radius:5px;text-decoration:none;font-size:14px;cursor:pointer}
.btn.sec{background:#6b7280}.btn.rojo{background:#b91c1c}
label{display:block;margin:12px 0 4px;font-weight:bold;font-size:14px}
input[type=text],input[type=number],select,textarea{width:100%;padding:8px;border:1px solid #d1d5db;border-radius:5px;font-size:14px}
.ok{background:#dcfce7;color:#166534;padding:10px;border-radius:5px;margin-bottom:12px}
.err{background:#fee2e2;color:#991b1b;padding:10px;border-radius:5px;margin-bottom:12px}
.err ul{margin:0;padding-left:18px}
.acciones{display:flex;gap:6px;align-items:center}
.acciones form{display:inline}
.top{display:flex;justify-content:space-between;align-items:center;margin-bottom:12px}
.tag{padding:2px 8px;border-radius:10px;font-size:12px;background:#e5e7eb}
</style>
</head>
<body>
@php
    $rolActual = \Illuminate\Support\Facades\DB::table('rol')->where('COD_ROL', auth()->user()->COD_ROL)->value('DES_ROL');
@endphp
<header>
    <nav>
        <a href="{{ url('/abastecimiento') }}">Inicio</a>
        <a href="{{ url('/abastecimiento/panel') }}">Panel</a>
        @if(in_array($rolActual, ['encargado_estacion','sub_administrador','administrador']))
            <a href="{{ url('/abastecimiento/solicitudes') }}">Solicitudes</a>
        @endif
        @if(in_array($rolActual, ['sub_administrador','administrador']))
            <a href="{{ url('/abastecimiento/gestion') }}">Gestión</a>
        @endif
        @if(in_array($rolActual, ['sub_administrador','administrador','supervisor']))
            <a href="{{ url('/abastecimiento/historial') }}">Historial</a>
        @endif
        @if(in_array($rolActual, ['administrador_deposito','administrador']))
            <a href="{{ url('/abastecimiento/plantas') }}">Plantas</a>
            <a href="{{ url('/abastecimiento/tanques') }}">Tanques</a>
        @endif
    </nav>
    <div>
        {{ auth()->user()->NOM_USU }} ({{ $rolActual }})
        <form method="POST" action="{{ route('logout') }}">@csrf<button class="salir" type="submit">Salir</button></form>
    </div>
</header>
<main>
    @if(session('ok'))<div class="ok">{{ session('ok') }}</div>@endif
    @if($errors->any())
        <div class="err"><ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    <div class="card">@yield('contenido')</div>
</main>
</body>
</html>