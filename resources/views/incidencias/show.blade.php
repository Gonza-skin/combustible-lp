@extends('modulos.layout')

@section('title', 'Detalle de incidencia')
@section('heading', 'Incidencias')

@section('content')
    <section class="panel">
        <h2>Detalle de la incidencia</h2>
        <dl class="details">
            <div><dt>Código</dt><dd>{{ $incidencia->COD_INC }}</dd></div>
            <div><dt>Fecha de registro</dt><dd>{{ $incidencia->FEC_INC }}</dd></div>
            <div><dt>Tipo</dt><dd>{{ str_replace('_', ' ', $incidencia->TIP_INC) }}</dd></div>
            <div><dt>Estado</dt><dd>{{ str_replace('_', ' ', $incidencia->ESD_INC) }}</dd></div>
            <div><dt>Abastecimiento</dt><dd>{{ $incidencia->COD_ABA ?: '—' }}</dd></div>
            <div><dt>Tanque</dt><dd>{{ $incidencia->COD_TAN ?: '—' }}</dd></div>
            <div><dt>Descripción</dt><dd>{{ $incidencia->DES_INC ?: '—' }}</dd></div>
            <div><dt>Acciones realizadas</dt><dd>{{ $incidencia->ACC_INC ?: '—' }}</dd></div>
            <div><dt>Fecha de resolución/cierre</dt><dd>{{ $incidencia->FRS_INC ?: '—' }}</dd></div>
            <div><dt>Registrado por (código)</dt><dd>{{ $incidencia->COD_USU ?: '—' }}</dd></div>
        </dl>
        <div class="actions" style="margin-top: 20px">
            <a class="button secondary" href="{{ route('incidencias.index') }}">Volver al listado</a>
            <a class="button" href="{{ route('incidencias.edit', $incidencia->COD_INC) }}">Editar</a>
        </div>
    </section>
@endsection
