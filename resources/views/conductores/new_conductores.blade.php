<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Registrar conductor</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f6f8] text-[#2c3e50] font-sans">
    <main class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">
        <div class="p-6">
            <h2 class="mb-6 text-3xl font-bold text-[#1F4E5F]">Registrar conductor</h2>

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

            <form action="{{ route('conductores.store') }}" method="POST" class="space-y-6 rounded-lg bg-white p-6 shadow-sm border border-gray-200">
                @csrf
                <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                    <div>
                        <label for="COD_USU" class="mb-1 block font-semibold text-gray-700">Usuario del sistema *</label>
                        <select id="COD_USU" name="COD_USU" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                            <option value="">Selecciona usuario</option>
                            @foreach($usuarios as $usuario)
                                <option value="{{ $usuario->COD_USU }}" @selected(old('COD_USU', '') == $usuario->COD_USU)>{{ $usuario->NOM_USU }} — {{ $usuario->EML_USU }}</option>
                            @endforeach
                        </select>
                        @error('COD_USU')<p class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="NOM_CON" class="mb-1 block font-semibold text-gray-700">Nombre completo *</label>
                        <input id="NOM_CON" name="NOM_CON" type="text" value="{{ old('NOM_CON', '') }}" maxlength="150" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                        @error('NOM_CON')<p class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="CI_CON" class="mb-1 block font-semibold text-gray-700">Cédula de identidad *</label>
                        <input id="CI_CON" name="CI_CON" type="text" value="{{ old('CI_CON', '') }}" maxlength="20" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                        @error('CI_CON')<p class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="LIC_CON" class="mb-1 block font-semibold text-gray-700">Número de licencia *</label>
                        <input id="LIC_CON" name="LIC_CON" type="text" value="{{ old('LIC_CON', '') }}" maxlength="20" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                        @error('LIC_CON')<p class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="CAT_LIC" class="mb-1 block font-semibold text-gray-700">Categoría de licencia</label>
                        <input id="CAT_LIC" name="CAT_LIC" type="text" value="{{ old('CAT_LIC', '') }}" maxlength="10" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                        @error('CAT_LIC')<p class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="TEL_CON" class="mb-1 block font-semibold text-gray-700">Teléfono (9 dígitos)</label>
                        <input id="TEL_CON" name="TEL_CON" type="tel" value="{{ old('TEL_CON', '') }}" maxlength="9" pattern="[0-9]{9}" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                        @error('TEL_CON')<p class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="ACT_CON" class="mb-1 block font-semibold text-gray-700">Estado *</label>
                        <select id="ACT_CON" name="ACT_CON" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                            <option value="1" @selected(old('ACT_CON', '1') == 1)>Activo</option>
                            <option value="0" @selected(old('ACT_CON', '1') == 0)>Inactivo</option>
                        </select>
                        @error('ACT_CON')<p class="mt-1 text-sm font-medium text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="mt-6 flex flex-wrap gap-3">
                    <button type="submit" class="rounded-lg bg-[#1F4E5F] px-5 py-2 font-semibold text-white shadow-sm transition hover:bg-[#2c7873]">Registrar conductor</button>
                    <a href="{{ route('conductores.index') }}" class="rounded-lg bg-gray-200 px-5 py-2 font-semibold text-gray-700 shadow-sm transition hover:bg-gray-300">Cancelar</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
