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
<style>.stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}.stat{background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px}.stat b{display:block;font-size:30px;color:#1e3a8a}.stat.rojo b{color:#b91c1c}.stat span{font-size:14px;color:#4b5563}</style>
@endsection