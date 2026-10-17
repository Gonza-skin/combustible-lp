@extends('abastecimiento.layout')
@section('titulo', 'Solicitud')
@section('contenido')
<h1>Solicitud #{{ $item->COD_SOL }}</h1>
<table>
<tr><th>Estación</th><td>{{ $item->NOM_EST }}</td></tr>
<tr><th>Combustible</th><td>{{ $item->DES_COM }}</td></tr>
<tr><th>Cantidad</th><td>{{ number_format($item->CAN_SOL, 2) }} L</td></tr>
<tr><th>Estado</th><td><span class="tag">{{ $item->ESD_SOL }}</span></td></tr>
<tr><th>Solicitada por</th><td>{{ $solicitante }}</td></tr>
<tr><th>Fecha</th><td>{{ $item->FEC_SOL }}</td></tr>
</table>
<p><a class="btn sec" href="{{ route('abast.solicitudes.index') }}">Volver</a></p>
@endsection