<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Control de cisternas y mantenimiento</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f6f8] text-[#2c3e50] font-sans">
    <main class="mx-auto max-w-4xl p-4 sm:p-6 lg:p-8">
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-6 border-b pb-2 text-2xl font-bold text-[#1F4E5F]">Registrar Nueva Cisterna</h2>

            @if($errors->any())
                <div class="mb-6 rounded-lg bg-red-100 p-4 text-red-800">
                    <p class="font-bold">Revisa los siguientes errores:</p>
                    <ul class="ml-4 mt-2 list-inside list-disc text-sm">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form action="{{ route('cisternas.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="PLC_CIS" class="mb-1 block font-semibold text-gray-700">Placa de la cisterna *</label>
                        <input id="PLC_CIS" name="PLC_CIS" type="text" value="{{ old('PLC_CIS', '') }}" maxlength="20" required placeholder="Ej. 1234-ABC" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                        @error('PLC_CIS')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="CAP_CIS" class="mb-1 block font-semibold text-gray-700">Capacidad en litros *</label>
                        <input id="CAP_CIS" name="CAP_CIS" type="number" value="{{ old('CAP_CIS', '') }}" min="0.01" step="0.01" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                        @error('CAP_CIS')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="COD_COM" class="mb-1 block font-semibold text-gray-700">Tipo de combustible *</label>
                        <select id="COD_COM" name="COD_COM" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                            <option value="">Selecciona combustible</option>
                            @foreach($combustibles as $combustible)<option value="{{ $combustible->COD_COM }}" @selected(old('COD_COM') == $combustible->COD_COM)>{{ $combustible->DES_COM }}</option>@endforeach
                        </select>
                        @error('COD_COM')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="COD_CON" class="mb-1 block font-semibold text-gray-700">Conductor asignado</label>
                        <select id="COD_CON" name="COD_CON" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                            <option value="">Sin conductor asignado</option>
                            @foreach($conductores as $conductor)<option value="{{ $conductor->COD_CON }}" @selected(old('COD_CON') == $conductor->COD_CON)>{{ $conductor->NOM_CON }}</option>@endforeach
                        </select>
                        @error('COD_CON')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="ESD_CIS" class="mb-1 block font-semibold text-gray-700">Estado *</label>
                        <select id="ESD_CIS" name="ESD_CIS" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                            @foreach(['disponible' => 'Disponible', 'asignada' => 'Asignada', 'en_transito' => 'En tránsito', 'mantenimiento' => 'Mantenimiento'] as $valor => $texto)
                                <option value="{{ $valor }}" @selected(old('ESD_CIS', 'disponible') == $valor)>{{ $texto }}</option>
                            @endforeach
                        </select>
                        @error('ESD_CIS')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="mt-8 flex flex-wrap gap-4 border-t pt-4">
                    <button type="submit" class="rounded-lg bg-[#1F4E5F] px-6 py-2 font-semibold text-white shadow-sm transition hover:bg-[#2c7873]">Registrar cisterna</button>
                    <a href="{{ route('cisternas.index') }}" class="rounded-lg bg-gray-200 px-6 py-2 font-semibold text-[#1F4E5F] shadow-sm transition hover:bg-gray-300">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
