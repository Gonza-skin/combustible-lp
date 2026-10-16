@extends('modulos.layout')

@section('title', 'Ventas de combustible')
@section('heading', 'Ventas de combustible')

@section('content')
    <section class="panel">
        <div class="actions" style="justify-content: space-between; margin-bottom: 18px">
            <div>
                <h2>Ventas registradas</h2>
                <p class="muted">Ventas de combustible de tu estación.</p>
            </div>
            <a class="button" href="{{ route('ventas.create') }}">Registrar venta</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Placa</th>
                        <th>Combustible</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventas as $venta)
                        <tr>
                            <td>{{ $venta->FEC_VEN }}</td>
                            <td>{{ $venta->PLACA }}</td>
                            <td>{{ $venta->COMBUSTIBLE }}</td>
                            <td>{{ $venta->CAN_VEN }}</td>
                            <td>{{ $venta->PRE_VEN }}</td>
                            <td>{{ $venta->VAL_VEN }}</td>
                            <td>
                                <div class="actions">
                                    <a class="button secondary" href="{{ route('ventas.show', $venta->COD_VEN) }}">Ver</a>
                                    <a class="button" href="{{ route('ventas.edit', $venta->COD_VEN) }}">Editar</a>
                                    <form method="POST" action="{{ route('ventas.destroy', $venta->COD_VEN) }}" onsubmit="return confirm('¿Eliminar esta venta?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button danger" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted">No hay ventas registradas para tu estación.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($ventas->hasPages())
            <div class="pagination">
                @if ($ventas->onFirstPage())
                    <span class="muted">Anterior</span>
                @else
                    <a href="{{ $ventas->previousPageUrl() }}">Anterior</a>
                @endif
                <span class="muted">Página {{ $ventas->currentPage() }} de {{ $ventas->lastPage() }}</span>
                @if ($ventas->hasMorePages())
                    <a href="{{ $ventas->nextPageUrl() }}">Siguiente</a>
                @else
                    <span class="muted">Siguiente</span>
                @endif
            </div>
        @endif
    </section>
@endsection
