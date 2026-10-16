<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de conductores</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f6f8] text-[#2c3e50] font-sans">
    <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
        <div class="p-6">
            <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-[#1F4E5F]">Gestión de conductores</h2>
                    <p class="mt-1 text-sm text-gray-500">Conductores y datos de licencia vinculados a usuarios del sistema.</p>
                </div>
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
            @if($errors->any())
                <div class="mb-4 rounded-lg bg-red-100 p-3 text-red-800 shadow-sm">
                    <p class="font-semibold">Revisa los siguientes errores:</p>
                    <ul class="list-inside list-disc">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <div class="mb-6 flex flex-wrap items-center justify-between gap-4 rounded-lg bg-white p-4 shadow-sm">
                <input
                    type="search"
                    id="busqueda"
                    placeholder="Buscar por nombre, CI, licencia..."
                    class="w-full rounded-lg border-gray-300 px-4 py-2 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F] md:w-96"
                >
                <a href="{{ route('conductores.create') }}" class="rounded-lg bg-[#1F4E5F] px-5 py-2 font-semibold text-white shadow-sm transition hover:bg-[#2c7873]">Registrar conductor</a>
            </div>

            <div class="overflow-x-auto rounded-lg border border-gray-200 bg-white shadow-sm">
                <table id="tablaConductores" class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-[#1F4E5F] text-white">
                        <tr>
                            @foreach(['Código','Nombre','CI','Licencia','Teléfono','Estado','Acciones'] as $col)
                                <th class="px-4 py-3 text-left font-semibold tracking-wide">{{ $col }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($conductores as $conductor)
                        <tr class="transition hover:bg-gray-50 fila-conductor">
                            <td class="px-4 py-3">{{ $conductor->COD_CON }}</td>
                            <td class="px-4 py-3 font-bold text-[#1F4E5F]">{{ $conductor->NOM_CON }}</td>
                            <td class="px-4 py-3">{{ $conductor->CI_CON }}</td>
                            <td class="px-4 py-3">{{ $conductor->LIC_CON }} @if($conductor->CAT_LIC) ({{ $conductor->CAT_LIC }}) @endif</td>
                            <td class="px-4 py-3">{{ $conductor->TEL_CON ?? 'No registrado' }}</td>
                            <td class="px-4 py-3">
                                <span class="rounded-full px-2 py-1 text-xs font-semibold {{ $conductor->ACT_CON ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-700' }}">
                                    {{ $conductor->ACT_CON ? 'Activo' : 'Inactivo' }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-2">
                                    <a class="rounded-lg bg-sky-100 px-3 py-1 font-medium text-sky-800 transition hover:bg-sky-200" href="{{ route('conductores.show', $conductor->COD_CON) }}">Ver</a>
                                    <a class="rounded-lg bg-amber-100 px-3 py-1 font-medium text-amber-800 transition hover:bg-amber-200" href="{{ route('conductores.edit', $conductor->COD_CON) }}">Editar</a>
                                    <form action="{{ route('conductores.destroy', $conductor->COD_CON) }}" method="POST" onsubmit="return confirm('¿Eliminar este conductor?')">
                                        @csrf @method('DELETE')
                                        <button class="rounded-lg bg-red-100 px-3 py-1 font-medium text-red-800 transition hover:bg-red-200" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr id="filaVaciaDB">
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">No se encontraron conductores registrados en el sistema.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>

                <div id="sinResultados" class="hidden px-4 py-8 text-center text-gray-500">
                    No se encontraron conductores con esa búsqueda.
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const busqueda = document.getElementById('busqueda');
            const filas = document.querySelectorAll('.fila-conductor');
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
