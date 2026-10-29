@extends('abastecimiento.layout')
@section('titulo', 'Gestión de abastecimiento')
@section('contenido')
<h1>Solicitudes pendientes de atender</h1>
<table>
<tr><th>Solicitud</th><th>Estación</th><th>Combustible</th><th>Cantidad (L)</th><th>Fecha</th><th></th></tr>
@forelse($pendientes as $s)
<tr>
    <td>#{{ $s->COD_SOL }}</td><td>{{ $s->NOM_EST }}</td><td>{{ $s->DES_COM }}</td>
    <td>{{ number_format($s->CAN_SOL, 2) }}</td><td>{{ $s->FEC_SOL }}</td>
    <td>
        <form method="POST" action="{{ route('abast.gestion.crear', $s) }}" onsubmit="return confirm('¿Crear abastecimiento para esta solicitud?')">
            @csrf<button class="btn" type="submit">Crear abastecimiento</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="6">No hay solicitudes pendientes.</td></tr>
@endforelse
</table>

<h1 style="margin-top:28px">Abastecimientos</h1>
<table>
<tr><th>#</th><th>Solicitud</th><th>Estación</th><th>Combustible</th><th>Cantidad (L)</th><th>Cisterna</th><th>Estado</th><th></th></tr>
@forelse($items as $a)
<tr>
    <td>{{ $a->COD_ABA }}</td><td>#{{ $a->COD_SOL }}</td><td>{{ $a->NOM_EST }}</td><td>{{ $a->DES_COM }}</td>
    <td>{{ number_format($a->CAN_ABA, 2) }}</td><td>{{ $a->PLC_CIS ?? '—' }}</td>
    <td><span class="tag">{{ $a->ESD_ABA }}</span></td>
    <td><a class="btn sec" href="{{ route('abast.gestion.show', $a) }}">Gestionar</a></td>
</tr>
@empty
<tr><td colspan="8">Todavía no hay abastecimientos.</td></tr>
@endforelse
</table>
@endsection