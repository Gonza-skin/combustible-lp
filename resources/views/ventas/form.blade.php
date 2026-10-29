@extends('modulos.layout')

@section('title', $titulo)
@section('heading', 'Ventas de combustible')

@section('content')
    <section class="panel">
        <h2>{{ $titulo }}</h2>
        <form method="POST" action="{{ $venta ? route('ventas.update', $venta) : route('ventas.store') }}">
            @csrf
            @if ($venta)
                @method('PUT')
            @endif

            <label for="COD_VEH">Automóvil</label>
            <select id="COD_VEH" name="COD_VEH" required>
                <option value="">Selecciona un automóvil</option>
                @foreach ($vehiculos as $vehiculo)
                    <option value="{{ $vehiculo->COD_VEH }}" @selected((string) old('COD_VEH', $venta?->COD_VEH) === (string) $vehiculo->COD_VEH)>
                        {{ $vehiculo->PLA_VEH }} — {{ $vehiculo->MOD_VEH }}
                    </option>
                @endforeach
            </select>
            @error('COD_VEH') <div class="field-error">{{ $message }}</div> @enderror

            <label for="COD_COM">Combustible</label>
            <select id="COD_COM" name="COD_COM" required>
                <option value="">Selecciona el combustible</option>
                @foreach ($combustibles as $combustible)
                    <option value="{{ $combustible->COD_COM }}" @selected((string) old('COD_COM', $venta?->COD_COM) === (string) $combustible->COD_COM)>
                        {{ $combustible->DES_COM }}
                    </option>
                @endforeach
            </select>
            @error('COD_COM') <div class="field-error">{{ $message }}</div> @enderror

            <label for="CAN_VEN">Cantidad</label>
            <input id="CAN_VEN" name="CAN_VEN" type="number" min="0.01" step="0.01" value="{{ old('CAN_VEN', $venta?->CAN_VEN) }}" required>
            @error('CAN_VEN') <div class="field-error">{{ $message }}</div> @enderror

            <label for="PRE_VEN">Precio unitario</label>
            <input id="PRE_VEN" name="PRE_VEN" type="number" min="0.01" step="0.01" value="{{ old('PRE_VEN', $venta?->PRE_VEN) }}" required>
            @error('PRE_VEN') <div class="field-error">{{ $message }}</div> @enderror

            <div class="actions" style="margin-top: 20px">
                <button class="button" type="submit">Guardar</button>
                <a class="button secondary" href="{{ route('ventas.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
