@extends('abastecimiento.layout')
@section('titulo', 'Panel')
@section('contenido')
<h1>Panel general</h1>
<div class="stats">
    <div class="stat"><b>{{ $estacionesActivas }}</b><span>Estaciones activas</span></div>
    <div class="stat rojo"><b>{{ $estacionesNivelBajo }}</b><span>Estaciones con nivel bajo</span></div>
    <div class="stat"><b>{{ $cisternasEnTransito }}</b><span>Cisternas en tránsito</span></div>
    <div class="stat rojo"><b>{{ $incidenciasAbiertas }}</b><span>Incidencias abiertas</span></div>
    <div class="stat"><b>{{ $solicitudesPendientes }}</b><span>Solicitudes pendientes</span></div>
    <div class="stat"><b>{{ $abastecimientosActivos }}</b><span>Abastecimientos en curso</span></div>
</div>
@endsection