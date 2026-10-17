@extends('abastecimiento.layout')
@section('titulo', 'Historial de estado')
@section('contenido')
<div class="top">
    <h1>Historial de estado de abastecimientos</h1>
    <form method="GET" action="{{ route('abast.historial.index') }}" style="display:flex;gap:6px;align-items:center">
        <input type="number" name="abastecimiento" min="1" placeholder="N° abastecimiento" value="{{ $filtro }}" style="width:170px">
        <button class="btn" type="submit">Filtrar</button>
        @if($filtro)<a class="btn sec" href="{{ route('abast.historial.index') }}">Quitar</a>@endif
    </form>
</div>
<table>
<tr><th>Fecha</th><th>Abast.</th><th>Estación</th><th>Estado</th><th>Usuario</th><th>Observación</th></tr>
@forelse($items as $h)
<tr>
    <td>{{ $h->FEC_HIS }}</td><td>#{{ $h->COD_ABA }}</td><td>{{ $h->NOM_EST }}</td>
    <td><span class="tag">{{ $h->ESD_HIS }}</span></td><td>{{ $h->NOM_USU }}</td><td>{{ $h->OBS_HIS }}</td>
</tr>
@empty
<tr><td colspan="6">No hay movimientos registrados.</td></tr>
@endforelse
</table>
@endsection