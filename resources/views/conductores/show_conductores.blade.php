<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detalle del conductor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f6f8] text-[#2c3e50] font-sans">
    <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
        <div class="p-6">
            <h2 class="mb-6 text-3xl font-bold text-[#1F4E5F]">Detalle del conductor</h2>

            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <dl class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Código</dt>
                        <dd class="mt-1 text-lg font-semibold text-[#1F4E5F]">{{ $conductor->COD_CON }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Usuario vinculado</dt>
                        <dd class="mt-1 text-lg font-semibold">{{ $conductor->USUARIO_NOMBRE ?? "No disponible" }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                        <dd class="mt-1 text-lg font-semibold">{{ $conductor->EML_USU ?? "No disponible" }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Nombre completo</dt>
                        <dd class="mt-1 text-lg font-semibold">{{ $conductor->NOM_CON }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Cédula de Identidad</dt>
                        <dd class="mt-1 text-lg font-semibold">{{ $conductor->CI_CON }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Número de Licencia</dt>
                        <dd class="mt-1 text-lg font-semibold">{{ $conductor->LIC_CON }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                        <dd class="mt-1 text-lg font-semibold">{{ $conductor->CAT_LIC ?? "No registrada" }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="mt-1 text-lg font-semibold">{{ $conductor->TEL_CON ?? "No registrado" }}</dd>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4 border border-gray-100">
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1">
                            <span class="rounded-full px-3 py-1 text-sm font-semibold {{ $conductor->ACT_CON ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                {{ $conductor->ACT_CON ? "Activo" : "Inactivo" }}
                            </span>
                        </dd>
                    </div>
                </dl>

                <div class="mt-8 flex gap-3">
                    <a href="{{ route('conductores.edit', $conductor->COD_CON) }}" class="rounded-lg bg-[#1F4E5F] px-5 py-2 font-semibold text-white shadow-sm transition hover:bg-[#2c7873]">Editar conductor</a>
                    <a href="{{ route('conductores.index') }}" class="rounded-lg bg-gray-200 px-5 py-2 font-semibold text-gray-700 shadow-sm transition hover:bg-gray-300">Volver</a>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
