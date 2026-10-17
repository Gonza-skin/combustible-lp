@extends('abastecimiento.layout')
@section('titulo', 'Inicio')
@section('contenido')
@php
    $rol = \Illuminate\Support\Facades\DB::table('rol')->where('COD_ROL', auth()->user()->COD_ROL)->value('DES_ROL');
    $secciones = [
        ['Solicitudes', 'Crear, editar y consultar solicitudes de abastecimiento', '/abastecimiento/solicitudes', ['encargado_estacion','sub_administrador','administrador']],
        ['Gestión de abastecimiento', 'Asignar cisterna y cambiar estados', '/abastecimiento/gestion', ['sub_administrador','administrador']],
        ['Historial de estado', 'Consulta de cambios de estado (solo lectura)', '/abastecimiento/historial', ['sub_administrador','administrador','supervisor']],
        ['Plantas o depósitos', 'Alta, edición y baja de plantas', '/abastecimiento/plantas', ['administrador_deposito','administrador']],
        ['Tanques', 'Alta, edición y baja de tanques', '/abastecimiento/tanques', ['administrador_deposito','administrador']],
    ];
    $visibles = array_filter($secciones, fn ($s) => in_array($rol, $s[3], true));
@endphp
<h1>Módulo de Abastecimiento</h1>
<p>Entraste como <strong>{{ $rol }}</strong>. Estas son tus secciones:</p>
<div class="grid">
@forelse($visibles as $s)
    <a class="tarjeta" href="{{ url($s[2]) }}"><strong>{{ $s[0] }}</strong><span>{{ $s[1] }}</span></a>
@empty
    <p>Tu rol no tiene secciones en este módulo.</p>
@endforelse
</div>
<style>.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:12px;margin-top:14px}.tarjeta{display:flex;flex-direction:column;gap:6px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:16px;color:#111827;text-decoration:none}.tarjeta:hover{border-color:#1e3a8a;background:#eef2ff}.tarjeta span{font-size:13px;color:#4b5563}</style>
@endsection