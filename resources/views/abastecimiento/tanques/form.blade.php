@extends('abastecimiento.layout')
@section('titulo', 'Tanque')
@section('contenido')
@php $esEdit = isset($item); @endphp
<h1>{{ $esEdit ? 'Editar tanque' : 'Nuevo tanque' }}</h1>
<form method="POST" action="{{ $esEdit ? route('abast.tanques.update', $item) : route('abast.tanques.store') }}">
    @csrf
    @if($esEdit) @method('PUT') @endif
    <label>Planta</label>
    <select name="COD_PLA" required>
        <option value="">-- elegir --</option>
        @foreach($plantas as $p)
            <option value="{{ $p->COD_PLA }}" @selected(old('COD_PLA', $item->COD_PLA ?? '') == $p->COD_PLA)>{{ $p->NOM_PLA }}</option>
        @endforeach
    </select>
    <label>Combustible</label>
    <select name="COD_COM" required>
        <option value="">-- elegir --</option>
        @foreach($combustibles as $c)
            <option value="{{ $c->COD_COM }}" @selected(old('COD_COM', $item->COD_COM ?? '') == $c->COD_COM)>{{ $c->DES_COM }}</option>
        @endforeach
    </select>
    <label>Nombre del tanque</label>
    <input type="text" name="NOM_TAN" value="{{ old('NOM_TAN', $item->NOM_TAN ?? '') }}" required maxlength="60">
    <label>Capacidad (litros)</label>
    <input type="number" step="0.01" min="0.01" name="CAP_TAN" value="{{ old('CAP_TAN', $item->CAP_TAN ?? '') }}" required>
    <label>Nivel actual (litros, no puede superar la capacidad)</label>
    <input type="number" step="0.01" min="0" name="NIV_TAN" value="{{ old('NIV_TAN', $item->NIV_TAN ?? 0) }}" required>
    <label>Estado</label>
    <select name="ESD_TAN" required>
        @foreach($estados as $e)
            <option value="{{ $e }}" @selected(old('ESD_TAN', $item->ESD_TAN ?? 'operativo') == $e)>{{ $e }}</option>
        @endforeach
    </select>
    <p><button class="btn" type="submit">Guardar</button> <a class="btn sec" href="{{ route('abast.tanques.index') }}">Cancelar</a></p>
</form>
@endsection