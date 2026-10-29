@extends('abastecimiento.layout')
@section('titulo', 'Abastecimiento')
@section('contenido')
<h1>Abastecimiento #{{ $item->COD_ABA }}</h1>
<table>
<tr><th>Solicitud</th><td>#{{ $item->COD_SOL }}</td></tr>
<tr><th>Estación</th><td>{{ $item->NOM_EST }}</td></tr>
<tr><th>Combustible</th><td>{{ $item->DES_COM }}</td></tr>
<tr><th>Cantidad</th><td>{{ number_format($item->CAN_ABA, 2) }} L</td></tr>
<tr><th>Cisterna</th><td>{{ $item->PLC_CIS ?? 'Sin asignar' }}</td></tr>
<tr><th>Estado</th><td><span class="tag">{{ $item->ESD_ABA }}</span></td></tr>
</table>

@if(in_array($item->ESD_ABA, ['pendiente','asignado']))
<h2 style="font-size:17px;margin-top:22px">Asignar cisterna</h2>
<form method="POST" action="{{ route('abast.gestion.asignar', $item) }}">
    @csrf
    <label>Cisterna disponible (mismo combustible)</label>
    <select name="COD_CIS" required>
        <option value="">-- elegir --</option>
        @foreach($cisternas as $c)
            <option value="{{ $c->COD_CIS }}">{{ $c->PLC_CIS }} (cap. {{ number_format($c->CAP_CIS, 2) }} L)</option>
        @endforeach
    </select>
    <label>Observación (opcional)</label>
    <input type="text" name="OBS_HIS" maxlength="255">
    <p><button class="btn" type="submit">Asignar</button></p>
</form>
@endif

@if(count($siguientes))
<h2 style="font-size:17px;margin-top:22px">Cambiar estado</h2>
<form method="POST" action="{{ route('abast.gestion.estado', $item) }}">
    @csrf @method('PATCH')
    <label>Nuevo estado</label>
    <select name="ESD_ABA" required>
        @foreach($siguientes as $e)<option value="{{ $e }}">{{ $e }}</option>@endforeach
    </select>
    <label>Observación (opcional)</label>
    <input type="text" name="OBS_HIS" maxlength="255">
    <p><button class="btn" type="submit">Cambiar estado</button></p>
</form>
@endif

<h2 style="font-size:17px;margin-top:22px">Historial</h2>
<table>
<tr><th>Fecha</th><th>Estado</th><th>Usuario</th><th>Observación</th></tr>
@forelse($historial as $h)
<tr><td>{{ $h->FEC_HIS }}</td><td><span class="tag">{{ $h->ESD_HIS }}</span></td><td>{{ $h->NOM_USU }}</td><td>{{ $h->OBS_HIS }}</td></tr>
@empty
<tr><td colspan="4">Sin movimientos.</td></tr>
@endforelse
</table>
<p><a class="btn sec" href="{{ route('abast.gestion.index') }}">Volver</a></p>
@endsection