@extends('modulos.layout')

@section('title', 'Detalle de venta')
@section('heading', 'Ventas de combustible')

@section('content')
    <section class="panel">
        <h2>Detalle de la venta</h2>
        <dl class="details">
            <div><dt>Código</dt><dd>{{ $venta->COD_VEN }}</dd></div>
            <div><dt>Fecha</dt><dd>{{ $venta->FEC_VEN }}</dd></div>
            <div><dt>Estación</dt><dd>{{ $venta->ESTACION }}</dd></div>
            <div><dt>Automóvil</dt><dd>{{ $venta->PLACA }}</dd></div>
            <div><dt>Combustible</dt><dd>{{ $venta->COMBUSTIBLE }}</dd></div>
            <div><dt>Cantidad</dt><dd>{{ $venta->CAN_VEN }}</dd></div>
            <div><dt>Precio unitario</dt><dd>{{ $venta->PRE_VEN }}</dd></div>
            <div><dt>Total</dt><dd>{{ $venta->VAL_VEN }}</dd></div>
            <div><dt>Registrado por</dt><dd>{{ $venta->USUARIO }}</dd></div>
        </dl>
        <div class="actions" style="margin-top: 20px">
            <a class="button secondary" href="{{ route('ventas.index') }}">Volver al listado</a>
            <a class="button" href="{{ route('ventas.edit', $venta->COD_VEN) }}">Editar</a>
        </div>
    </section>
@endsection
