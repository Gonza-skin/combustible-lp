@extends('abastecimiento.layout')
@section('titulo', 'Editar solicitud')
@section('contenido')
<h1>Editar solicitud #{{ $item->COD_SOL }}</h1>
<form method="POST" action="{{ route('abast.solicitudes.update', $item->COD_SOL) }}">
    @csrf @method('PUT')
    <label>Estación</label>
    <input type="text" value="{{ $estacion }}" disabled>
    <label>Combustible</label>
    <select name="COD_COM" required>
        @foreach($combustibles as $c)
            <option value="{{ $c->COD_COM }}" @selected(old('COD_COM', $item->COD_COM) == $c->COD_COM)>{{ $c->DES_COM }}</option>
        @endforeach
    </select>
    <label>Cantidad solicitada (litros)</label>
    <input type="number" step="0.01" min="0.01" name="CAN_SOL" value="{{ old('CAN_SOL', $item->CAN_SOL) }}" required>
    <p><button class="btn" type="submit">Guardar</button> <a class="btn sec" href="{{ route('abast.solicitudes.index') }}">Cancelar</a></p>
</form>
@endsection