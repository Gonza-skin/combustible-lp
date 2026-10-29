@extends('abastecimiento.layout')
@section('titulo', 'Planta')
@section('contenido')
@php $esEdit = isset($item); @endphp
<h1>{{ $esEdit ? 'Editar planta' : 'Nueva planta' }}</h1>
<form method="POST" action="{{ $esEdit ? route('abast.plantas.update', $item) : route('abast.plantas.store') }}">
    @csrf
    @if($esEdit) @method('PUT') @endif
    <label>Nombre</label>
    <input type="text" name="NOM_PLA" value="{{ old('NOM_PLA', $item->NOM_PLA ?? '') }}" required maxlength="120">
    <label>Ubicación</label>
    <input type="text" name="UBI_PLA" value="{{ old('UBI_PLA', $item->UBI_PLA ?? '') }}" maxlength="200">
    <label><input type="checkbox" name="ACT_PLA" value="1" @checked(old('_token') ? old('ACT_PLA') : ($item->ACT_PLA ?? true))> Activa</label>
    <p><button class="btn" type="submit">Guardar</button> <a class="btn sec" href="{{ route('abast.plantas.index') }}">Cancelar</a></p>
</form>
@endsection