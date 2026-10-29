@extends('automoviles.layout')

@section('title', 'Detalle')

@section('content')
    <section class="panel">
        <h2>Detalle del automóvil</h2>
        <dl class="details">
            <div><dt>Código</dt><dd>{{ $registro->COD_VEH }}</dd></div>
            <div><dt>Placa</dt><dd>{{ $registro->PLA_VEH }}</dd></div>
            <div><dt>Tipo</dt><dd>{{ $registro->TIP_VEH }}</dd></div>
            <div><dt>Modelo</dt><dd>{{ $registro->MOD_VEH }}</dd></div>
            <div><dt>Propietario</dt><dd>{{ $registro->NOM_PROP ?: '—' }}</dd></div>
            <div><dt>Teléfono</dt><dd>{{ $registro->TEL_PROP ?: '—' }}</dd></div>
            <div><dt>Estación</dt><dd>{{ $registro->estacion?->NOM_EST ?: 'Sin asignar' }}</dd></div>
        </dl>
        <div class="actions" style="margin-top: 20px">
            <a class="button secondary" href="{{ route('automoviles.index') }}">Volver al listado</a>
            @if ($esAdministrador)
                <a class="button" href="{{ route('automoviles.edit', $registro) }}">Editar</a>
            @endif
        </div>
    </section>
@endsection
