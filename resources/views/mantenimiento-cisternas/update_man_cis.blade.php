<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mantenimiento de Cisternas</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-800">
    <div class="mx-auto max-w-4xl p-6">
        <h2 class="mb-6 text-3xl font-bold text-[#1F4E5F]">Editar mantenimiento #{{ $mantenimiento->COD_MAN }}</h2>

        @if($errors->any())
            <div class="mb-6 rounded-lg border-l-4 border-red-500 bg-red-50 p-4 text-red-700 shadow-sm">
                <p class="mb-2 font-bold">Revisa los siguientes errores:</p>
                <ul class="list-inside list-disc text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm md:p-8">
            <form action="{{ route('mantenimiento-cisternas.update', $mantenimiento->COD_MAN) }}" method="POST" class="space-y-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <label for="COD_CIS" class="mb-1.5 block font-medium text-slate-700">Cisterna *</label>
                        <select id="COD_CIS" name="COD_CIS" required class="w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                            <option value="">Selecciona cisterna</option>
                            @foreach($cisternas as $cisterna)
                                <option value="{{ $cisterna->COD_CIS }}" @selected(old('COD_CIS', $mantenimiento->COD_CIS) == $cisterna->COD_CIS)>
                                    {{ $cisterna->PLC_CIS }} — {{ number_format($cisterna->CAP_CIS, 2, ',', '.') }} L
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="TIP_MAN" class="mb-1.5 block font-medium text-slate-700">Tipo de mantenimiento *</label>
                        <select id="TIP_MAN" name="TIP_MAN" required class="w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                            @foreach(['preventivo' => 'Preventivo', 'correctivo' => 'Correctivo', 'documentacion' => 'Documentación'] as $valor => $texto)
                                <option value="{{ $valor }}" @selected(old('TIP_MAN', $mantenimiento->TIP_MAN) == $valor)>{{ $texto }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="FEC_MAN" class="mb-1.5 block font-medium text-slate-700">Fecha del mantenimiento *</label>
                        <input id="FEC_MAN" name="FEC_MAN" type="date" max="{{ date('Y-m-d') }}" value="{{ old('FEC_MAN', $mantenimiento->FEC_MAN) }}" required class="w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]">
                    </div>

                    <div class="md:col-span-2">
                        <label for="DES_MAN" class="mb-1.5 block font-medium text-slate-700">Descripción</label>
                        <textarea id="DES_MAN" name="DES_MAN" rows="4" class="w-full rounded-lg border-gray-300 bg-gray-50 p-2.5 text-sm shadow-sm focus:border-[#1F4E5F] focus:ring-[#1F4E5F]" placeholder="Detalle del trabajo realizado">{{ old('DES_MAN', $mantenimiento->DES_MAN) }}</textarea>
                    </div>
                </div>

                <div class="mt-8 flex flex-wrap gap-3 border-t border-gray-100 pt-6">
                    <button type="submit" class="rounded-lg bg-[#1F4E5F] px-6 py-2.5 font-medium text-white shadow-sm transition hover:bg-[#163a46]">Guardar cambios</button>
                    <a href="{{ route('mantenimiento-cisternas.index') }}" class="rounded-lg bg-gray-100 px-6 py-2.5 font-medium text-gray-700 transition hover:bg-gray-200">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
