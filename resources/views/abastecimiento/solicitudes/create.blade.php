@extends('abastecimiento.layout')
@section('titulo', 'Nueva solicitud')
@section('contenido')
<h1>Nueva solicitud de abastecimiento</h1>
<form method="POST" action="{{ route('abast.solicitudes.store') }}">
    @csrf
    <label>Estación</label>
    <input type="text" value="{{ $estacion }}" disabled>
    <label>Combustible</label>
    <select name="COD_COM" required>
        <option value="">-- elegir --</option>
        @foreach($combustibles as $c)
            <option value="{{ $c->COD_COM }}" @selected(old('COD_COM') == $c->COD_COM)>{{ $c->DES_COM }}</option>
        @endforeach
    </select>
    <label>Cantidad solicitada (litros)</label>
    <input type="number" step="0.01" min="0.01" name="CAN_SOL" value="{{ old('CAN_SOL') }}" required>
    <p><button class="btn" type="submit">Enviar solicitud</button> <a class="btn sec" href="{{ route('abast.solicitudes.index') }}">Cancelar</a></p>
</form>
@endsection