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
<body class="min-h-screen bg-[#f4f6f8] text-[#2c3e50] font-sans">
    @include('partials.tema-body')
    @include('partials.dashboard-sidebar')
    <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
        <div class="p-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <h2 class="text-3xl font-bold text-[#1F4E5F]">Mantenimiento de Cisternas</h2>
                <a href="{{ route('dashboard') }}" class="rounded-lg bg-gray-200 px-4 py-2 font-semibold text-[#1F4E5F] shadow-sm transition hover:bg-gray-300">
                    Volver al Dashboard
                </a>
            </div>

            @if(session('success'))
                <div class="mb-4 rounded-lg bg-green-100 p-3 font-medium text-green-800 shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 rounded-lg bg-red-100 p-3 font-medium text-red-800 shadow-sm">{{ session('error') }}</div>
            @endif

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4 rounded-lg bg-white p-4 shadow-sm">
                <input
                    type="search"
                    id="busqueda"
                    placeholder="Buscar por placa, tipo o descripción..."
                    class="w-full rounded-lg border-gray-300 px-4 py-2 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F] md:w-96"
                >
                <a href="{{ route('mantenimiento-cisternas.create') }}" class="rounded-lg bg-[#1F4E5F] px-5 py-2 font-semibold text-white shadow-sm transition hover:bg-[#2c7873]">Registrar mantenimiento</a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                <table id="tablaMantenimientos" class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-[#1F4E5F] text-white">
                        <tr>
                            @foreach(['Código', 'Cisterna', 'Tipo', 'Fecha', 'Descripción', 'Acciones'] as $col)
                                <th class="px-4 py-3 text-left font-semibold tracking-wide">{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse ($mantenimientos as $mantenimiento)
                        <tr class="transition hover:bg-gray-50 fila-mantenimiento">
                            <td class="px-4 py-3">{{ $mantenimiento->COD_MAN }}</td>
                            <td class="px-4 py-3 font-bold text-[#1F4E5F]">{{ $mantenimiento->PLC_CIS }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700">
                                    {{ ucfirst($mantenimiento->TIP_MAN) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ \Illuminate\Support\Carbon::parse($mantenimiento->FEC_MAN)->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit($mantenimiento->DES_MAN ?? 'Sin descripción', 60) }}</td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a class="rounded-lg bg-sky-100 px-3 py-1 font-medium text-sky-800 transition hover:bg-sky-200" href="{{ route('mantenimiento-cisternas.show', $mantenimiento) }}">Ver</a>
                                    <a class="rounded-lg bg-amber-100 px-3 py-1 font-medium text-amber-800 transition hover:bg-amber-200" href="{{ route('mantenimiento-cisternas.edit', $mantenimiento) }}">Editar</a>
                                    <form action="{{ route('mantenimiento-cisternas.destroy', $mantenimiento) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro de mantenimiento?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg bg-red-100 px-3 py-1 font-medium text-red-800 transition hover:bg-red-200" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="filaVaciaDB">
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">Todavía no hay mantenimientos registrados en el sistema.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div id="sinResultados" class="hidden px-4 py-8 text-center text-gray-500">
                    No se encontraron mantenimientos con esa búsqueda.
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const busqueda = document.getElementById('busqueda');
            const filas = document.querySelectorAll('.fila-mantenimiento');
            const mensaje = document.getElementById('sinResultados');
            const filaVaciaDB = document.getElementById('filaVaciaDB');

            if (!filaVaciaDB && filas.length > 0) {
                busqueda.addEventListener('input', function () {
                    const texto = this.value.toLowerCase().trim();
                    let visibles = 0;

                    filas.forEach(function (fila) {
                        const coincide = fila.textContent.toLowerCase().includes(texto);
                        fila.style.display = coincide ? '' : 'none';

                        if (coincide) visibles++;
                    });

                    if (visibles === 0) {
                        mensaje.classList.remove('hidden');
                    } else {
                        mensaje.classList.add('hidden');
                    }
                });
            } else if (filaVaciaDB) {
                busqueda.disabled = true;
                busqueda.placeholder = "No hay datos para buscar...";
            }
        });
    </script>
</body>
</html>
