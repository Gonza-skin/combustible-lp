<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mantenimiento de Cisternas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.tema-head')
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    @include('partials.tema-body')
    @include('partials.dashboard-sidebar')
    <div class="mx-auto max-w-4xl p-6">
        <h2 class="mb-6 text-3xl font-bold text-[#1F4E5F]">Detalle del mantenimiento</h2>

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-100 bg-gray-50 px-6 py-4">
                <h3 class="font-semibold text-gray-700">Información General (Registro #{{ $mantenimiento->COD_MAN }})</h3>
            </div>

            <dl class="grid grid-cols-1 gap-0 sm:grid-cols-2">
                <div class="border-b border-gray-100 px-6 py-5 sm:border-r">
                    <dt class="text-sm font-medium text-gray-500">Cisterna</dt>
                    <dd class="mt-1 text-lg font-bold text-[#1F4E5F]">{{ $mantenimiento->PLC_CIS }}</dd>
                </div>
                <div class="border-b border-gray-100 px-6 py-5">
                    <dt class="text-sm font-medium text-gray-500">Capacidad de cisterna</dt>
                    <dd class="mt-1 font-semibold text-gray-900">{{ number_format($mantenimiento->CAP_CIS, 2, ",", ".") }} litros</dd>
                </div>
                <div class="border-b border-gray-100 px-6 py-5 sm:border-r">
                    <dt class="text-sm font-medium text-gray-500">Tipo de mantenimiento</dt>
                    <dd class="mt-1 font-semibold text-gray-900">
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                            {{ ucfirst($mantenimiento->TIP_MAN) }}
                        </span>
                    </dd>
                </div>
                <div class="border-b border-gray-100 px-6 py-5">
                    <dt class="text-sm font-medium text-gray-500">Fecha</dt>
                    <dd class="mt-1 font-semibold text-gray-900">{{ \Illuminate\Support\Carbon::parse($mantenimiento->FEC_MAN)->format("d/m/Y") }}</dd>
                </div>
                <div class="px-6 py-5 sm:col-span-2">
                    <dt class="text-sm font-medium text-gray-500">Descripción completa</dt>
                    <dd class="mt-2 whitespace-pre-line rounded-lg bg-gray-50 p-4 text-gray-700 border border-gray-100">{{ $mantenimiento->DES_MAN ?? 'Sin descripción registrada.' }}</dd>
                </div>
            </dl>
        </div>

        <div class="mt-6 flex gap-3">
            <a href="{{ route('mantenimiento-cisternas.edit', $mantenimiento->COD_MAN) }}" class="rounded-lg bg-[#1F4E5F] px-6 py-2.5 font-medium text-white shadow-sm transition hover:bg-[#163a46]">Editar registro</a>
            <a href="{{ route('mantenimiento-cisternas.index') }}" class="rounded-lg bg-gray-200 px-6 py-2.5 font-medium text-gray-800 transition hover:bg-gray-300">Volver a la lista</a>
        </div>
    </div>
</body>
</html>
