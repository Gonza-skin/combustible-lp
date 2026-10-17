@extends('modulos.layout')

@section('title', $titulo)
@section('heading', 'Incidencias')

@section('content')
    <section class="panel">
        <h2>{{ $titulo }}</h2>
        <form method="POST" action="{{ $incidencia ? route('incidencias.update', $incidencia->COD_INC) : route('incidencias.store') }}">
            @csrf
            @if ($incidencia)
                @method('PUT')
            @endif

            <label for="TIP_INC">Tipo de incidencia</label>
            <select id="TIP_INC" name="TIP_INC" required>
                <option value="">Selecciona un tipo</option>
                @foreach ($tipos as $tipo)
                    <option value="{{ $tipo }}" @selected(old('TIP_INC', $incidencia?->TIP_INC) === $tipo)>{{ str_replace('_', ' ', ucfirst($tipo)) }}</option>
                @endforeach
            </select>
            @error('TIP_INC') <div class="field-error">{{ $message }}</div> @enderror

            <label for="ESD_INC">Estado</label>
            <select id="ESD_INC" name="ESD_INC" required>
                @foreach ($estados as $estado)
                    <option value="{{ $estado }}" @selected(old('ESD_INC', $incidencia?->ESD_INC ?? 'detectada') === $estado)>{{ str_replace('_', ' ', ucfirst($estado)) }}</option>
                @endforeach
            </select>
            @error('ESD_INC') <div class="field-error">{{ $message }}</div> @enderror

            <label for="COD_ABA">Abastecimiento relacionado</label>
            <select id="COD_ABA" name="COD_ABA">
                <option value="">Sin abastecimiento relacionado</option>
                @foreach ($abastecimientos as $abastecimiento)
                    <option value="{{ $abastecimiento->COD_ABA }}" @selected((string) old('COD_ABA', $incidencia?->COD_ABA) === (string) $abastecimiento->COD_ABA)>
                        #{{ $abastecimiento->COD_ABA }} — {{ $abastecimiento->ESD_ABA }}
                    </option>
                @endforeach
            </select>
            @error('COD_ABA') <div class="field-error">{{ $message }}</div> @enderror

            <label for="COD_TAN">Tanque relacionado</label>
            <select id="COD_TAN" name="COD_TAN">
                <option value="">Sin tanque relacionado</option>
                @foreach ($tanques as $tanque)
                    <option value="{{ $tanque->COD_TAN }}" @selected((string) old('COD_TAN', $incidencia?->COD_TAN) === (string) $tanque->COD_TAN)>
                        {{ $tanque->NOM_TAN }}
                    </option>
                @endforeach
            </select>
            @error('COD_TAN') <div class="field-error">{{ $message }}</div> @enderror

            <label for="DES_INC">Descripción</label>
            <textarea id="DES_INC" name="DES_INC" rows="4">{{ old('DES_INC', $incidencia?->DES_INC) }}</textarea>
            @error('DES_INC') <div class="field-error">{{ $message }}</div> @enderror

            <label for="ACC_INC">Acciones realizadas</label>
            <textarea id="ACC_INC" name="ACC_INC" rows="4">{{ old('ACC_INC', $incidencia?->ACC_INC) }}</textarea>
            @error('ACC_INC') <div class="field-error">{{ $message }}</div> @enderror

            <label for="FRS_INC">Fecha de resolución o cierre</label>
            <input id="FRS_INC" name="FRS_INC" type="datetime-local" value="{{ old('FRS_INC', $incidencia?->FRS_INC ? \Illuminate\Support\Carbon::parse($incidencia->FRS_INC)->format('Y-m-d\TH:i') : '') }}">
            <p class="muted">Obligatoria cuando el estado sea resuelta o cerrada.</p>
            @error('FRS_INC') <div class="field-error">{{ $message }}</div> @enderror

            <div class="actions" style="margin-top: 20px">
                <button class="button" type="submit">Guardar</button>
                <a class="button secondary" href="{{ route('incidencias.index') }}">Cancelar</a>
            </div>
        </form>
    </section>
@endsection
