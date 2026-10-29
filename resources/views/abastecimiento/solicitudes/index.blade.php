@extends('abastecimiento.layout')
@section('titulo', 'Solicitudes')
@section('contenido')
<div class="top">
    <h1>Solicitudes de abastecimiento</h1>
    @if($esEncargado)<a class="btn" href="{{ route('abast.solicitudes.create') }}">+ Nueva solicitud</a>@endif
</div>
<table>
<tr><th>#</th><th>Estación</th><th>Combustible</th><th>Cantidad (L)</th><th>Estado</th><th>Fecha</th><th></th></tr>
@forelse($items as $s)
<tr>
    <td>{{ $s->COD_SOL }}</td><td>{{ $s->NOM_EST }}</td><td>{{ $s->DES_COM }}</td>
    <td>{{ number_format($s->CAN_SOL, 2) }}</td>
    <td><span class="tag">{{ $s->ESD_SOL }}</span></td>
    <td>{{ $s->FEC_SOL }}</td>
    <td class="acciones">
        <a class="btn sec" href="{{ route('abast.solicitudes.show', $s) }}">Ver</a>
        @if($esEncargado && $s->ESD_SOL === 'pendiente')
        <a class="btn sec" href="{{ route('abast.solicitudes.edit', $s) }}">Editar</a>
        <form method="POST" action="{{ route('abast.solicitudes.cancelar', $s) }}" onsubmit="return confirm('¿Cancelar esta solicitud?')">
            @csrf @method('PATCH')<button class="btn rojo" type="submit">Cancelar</button>
        </form>
        @endif
    </td>
</tr>
@empty
<tr><td colspan="7">No hay solicitudes.</td></tr>
@endforelse
</table>
@endsection