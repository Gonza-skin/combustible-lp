@extends('abastecimiento.layout')
@section('titulo', 'Tanques')
@section('contenido')
<div class="top"><h1>Tanques de almacenamiento</h1><a class="btn" href="{{ route('abast.tanques.create') }}">+ Nuevo tanque</a></div>
<table>
<tr><th>#</th><th>Tanque</th><th>Planta</th><th>Combustible</th><th>Capacidad</th><th>Nivel</th><th>Estado</th><th></th></tr>
@forelse($items as $t)
<tr>
    <td>{{ $t->COD_TAN }}</td><td>{{ $t->NOM_TAN }}</td><td>{{ $t->NOM_PLA }}</td><td>{{ $t->DES_COM }}</td>
    <td>{{ number_format($t->CAP_TAN, 2) }}</td><td>{{ number_format($t->NIV_TAN, 2) }}</td>
    <td><span class="tag">{{ $t->ESD_TAN }}</span></td>
    <td class="acciones">
        <a class="btn sec" href="{{ route('abast.tanques.edit', $t->COD_TAN) }}">Editar</a>
        <form method="POST" action="{{ route('abast.tanques.destroy', $t->COD_TAN) }}" onsubmit="return confirm('¿Eliminar este tanque?')">
            @csrf @method('DELETE')<button class="btn rojo" type="submit">Eliminar</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="8">No hay tanques registrados.</td></tr>
@endforelse
</table>
@endsection