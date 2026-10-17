@extends('modulos.layout')

@section('title', 'Reportar estado de estación')
@section('heading', 'Reporte ciudadano')

@section('content')
    <section class="panel">
        <h2>Reportar estado de una estación</h2>
        <p class="muted">Comparte información actual sobre las filas y la disponibilidad de combustible.</p>

        <form method="POST" action="{{ route('reportes-ciudadanos.store') }}">
            @csrf

            <label for="COD_EST">Estación</label>
            <select id="COD_EST" name="COD_EST" required>
                <option value="">Selecciona una estación</option>
                @foreach ($estaciones as $estacion)
                    <option value="{{ $estacion->COD_EST }}" @selected((string) old('COD_EST') === (string) $estacion->COD_EST)>
                        {{ $estacion->NOM_EST }}{{ $estacion->ZON_EST ? ' — '.$estacion->ZON_EST : '' }}
                    </option>
                @endforeach
            </select>
            @error('COD_EST') <div class="field-error">{{ $message }}</div> @enderror

            <label for="FIL_REP">Estado de la fila</label>
            <select id="FIL_REP" name="FIL_REP">
                <option value="">Sin información</option>
                @foreach (['sin_fila' => 'Sin fila', 'corta' => 'Corta', 'moderada' => 'Moderada', 'larga' => 'Larga'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('FIL_REP') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('FIL_REP') <div class="field-error">{{ $message }}</div> @enderror

            <label for="DIS_REP">Disponibilidad de combustible</label>
            <select id="DIS_REP" name="DIS_REP">
                <option value="">Sin información</option>
                @foreach (['disponible' => 'Disponible', 'poco_stock' => 'Poco stock', 'agotado' => 'Agotado'] as $value => $label)
                    <option value="{{ $value }}" @selected(old('DIS_REP') === $value)>{{ $label }}</option>
                @endforeach
            </select>
            @error('DIS_REP') <div class="field-error">{{ $message }}</div> @enderror

            <label for="TES_REP">Tiempo estimado de espera (minutos)</label>
            <input id="TES_REP" name="TES_REP" type="number" min="0" step="1" value="{{ old('TES_REP') }}">
            @error('TES_REP') <div class="field-error">{{ $message }}</div> @enderror

            <div class="actions" style="margin-top: 20px">
                <button class="button" type="submit">Enviar reporte</button>
                <a class="button secondary" href="{{ route('dashboard') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
