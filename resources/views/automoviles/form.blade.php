@extends('automoviles.layout')

@section('title', $titulo)

@section('content')
    <section class="panel">
        <h2>{{ $titulo }}</h2>
        @if ($errors->any())
            <div class="notice error" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ $automovil ? route('automoviles.update', $automovil->COD_VEH) : route('automoviles.store') }}">
            @csrf
            @if ($automovil)
                @method('PUT')
            @endif

            <label for="PLA_VEH">Placa</label>
            <input id="PLA_VEH" name="PLA_VEH" value="{{ old('PLA_VEH', $automovil?->PLA_VEH) }}" maxlength="15" required>
            @error('PLA_VEH') <div class="field-error">{{ $message }}</div> @enderror

            <label for="TIP_VEH">Tipo de vehículo</label>
            <input id="TIP_VEH" name="TIP_VEH" value="{{ old('TIP_VEH', $automovil?->TIP_VEH) }}" maxlength="20" required>
            @error('TIP_VEH') <div class="field-error">{{ $message }}</div> @enderror

            <label for="MOD_VEH">Modelo</label>
            <input id="MOD_VEH" name="MOD_VEH" value="{{ old('MOD_VEH', $automovil?->MOD_VEH) }}" maxlength="60" required>
            @error('MOD_VEH') <div class="field-error">{{ $message }}</div> @enderror

            <label for="NOM_PROP">Nombre del propietario</label>
            <input id="NOM_PROP" name="NOM_PROP" value="{{ old('NOM_PROP', $automovil?->NOM_PROP) }}" maxlength="150">
            @error('NOM_PROP') <div class="field-error">{{ $message }}</div> @enderror

            <label for="TEL_PROP">Teléfono del propietario (9 dígitos)</label>
            <input id="TEL_PROP" name="TEL_PROP" inputmode="numeric" value="{{ old('TEL_PROP', $automovil?->TEL_PROP) }}" maxlength="9">
            @error('TEL_PROP') <div class="field-error">{{ $message }}</div> @enderror

            <label for="COD_EST">Estación asignada</label>
            <select id="COD_EST" name="COD_EST" required>
                <option value="">Selecciona una estación</option>
                @foreach ($estaciones as $estacion)
                    <option value="{{ $estacion->COD_EST }}" @selected((string) old('COD_EST', $automovil?->COD_EST) === (string) $estacion->COD_EST)>
                        {{ $estacion->NOM_EST }}
                    </option>
                @endforeach
            </select>
            @error('COD_EST') <div class="field-error">{{ $message }}</div> @enderror

            <div class="actions" style="margin-top: 20px">
                <button class="button" type="submit">Guardar</button>
                <a class="button secondary" href="{{ route('automoviles.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
