<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de cisternas y mantenimiento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.tema-head')
</head>
<body class="min-h-screen bg-[#f4f6f8] text-[#2c3e50] font-sans">
    @include('partials.tema-body')
    @include('partials.dashboard-sidebar')
    <main class="mx-auto max-w-4xl p-4 sm:p-6 lg:p-8">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-6 border-b pb-2 text-2xl font-bold text-[#1F4E5F]">Detalle de Cisterna: {{ $cisterna->PLC_CIS }}</h2>

            <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <div class="rounded-lg border border-gray-100 bg-[#f4f6f8] p-4">
                    <dt class="text-sm font-semibold text-gray-500">Código Sistema</dt>
                    <dd class="mt-1 text-lg font-bold text-[#1F4E5F]">{{ $cisterna->COD_CIS }}</dd>
                </div>
                <div class="rounded-lg border border-gray-100 bg-[#f4f6f8] p-4">
                    <dt class="text-sm font-semibold text-gray-500">Placa Vehicular</dt>
                    <dd class="mt-1 text-lg font-bold text-[#1F4E5F]">{{ $cisterna->PLC_CIS }}</dd>
                </div>
                <div class="rounded-lg border border-gray-100 bg-[#f4f6f8] p-4">
                    <dt class="text-sm font-semibold text-gray-500">Capacidad</dt>
                    <dd class="mt-1 text-lg font-bold text-[#1F4E5F]">{{ number_format($cisterna->CAP_CIS, 2, ",", ".") }} L</dd>
                </div>
                <div class="rounded-lg border border-gray-100 bg-[#f4f6f8] p-4">
                    <dt class="text-sm font-semibold text-gray-500">Combustible Asignado</dt>
                    <dd class="mt-1 text-lg font-bold text-[#1F4E5F]">{{ $cisterna->DES_COM ?? 'No disponible' }}</dd>
                </div>
                <div class="rounded-lg border border-gray-100 bg-[#f4f6f8] p-4">
                    <dt class="text-sm font-semibold text-gray-500">Conductor</dt>
                    <dd class="mt-1 text-lg font-bold text-[#1F4E5F]">{{ $cisterna->NOM_CON ?? 'Sin asignar' }}</dd>
                </div>
                <div class="rounded-lg border border-gray-100 bg-[#f4f6f8] p-4">
                    <dt class="text-sm font-semibold text-gray-500">Estado Actual</dt>
                    <dd class="mt-1 text-lg font-bold text-[#2c7873]">{{ ucfirst(str_replace('_', ' ', $cisterna->ESD_CIS)) }}</dd>
                </div>
            </dl>

            <div class="mt-8 flex gap-4 border-t pt-4">
                <a href="{{ route('cisternas.edit', $cisterna->COD_CIS) }}" class="rounded-lg bg-[#1F4E5F] px-6 py-2 font-semibold text-white shadow-sm transition hover:bg-[#2c7873]">Editar Cisterna</a>
                <a href="{{ route('cisternas.index') }}" class="rounded-lg bg-gray-200 px-6 py-2 font-semibold text-[#1F4E5F] shadow-sm transition hover:bg-gray-300">Volver a la lista</a>
            </div>
        </div>
    </main>
</body>
</html>
