@extends('abastecimiento.layout')
@section('titulo', 'Plantas')
@section('contenido')
<div class="top"><h1>Plantas o depósitos</h1><a class="btn" href="{{ route('abast.plantas.create') }}">+ Nueva planta</a></div>
<table>
<tr><th>#</th><th>Nombre</th><th>Ubicación</th><th>Tanques</th><th>Activa</th><th></th></tr>
@forelse($items as $p)
<tr>
    <td>{{ $p->COD_PLA }}</td><td>{{ $p->NOM_PLA }}</td><td>{{ $p->UBI_PLA }}</td><td>{{ $p->tanques_count }}</td>
    <td>{{ $p->ACT_PLA ? 'Sí' : 'No' }}</td>
    <td class="acciones">
        <a class="btn sec" href="{{ route('abast.plantas.edit', $p->COD_PLA) }}">Editar</a>
        <form method="POST" action="{{ route('abast.plantas.destroy', $p->COD_PLA) }}" onsubmit="return confirm('¿Eliminar esta planta?')">
            @csrf @method('DELETE')<button class="btn rojo" type="submit">Eliminar</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="6">No hay plantas registradas.</td></tr>
@endforelse
</table>
@endsection