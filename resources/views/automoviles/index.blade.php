@extends('automoviles.layout')

@section('title', 'Listado')

@section('content')
    <section class="panel">
        <h2>Automóviles</h2>
        <p class="muted">Listado de placa, tipo, modelo, propietario, teléfono y estación asignada.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Placa</th>
                        <th>Tipo</th>
                        <th>Modelo</th>
                        <th>Propietario</th>
                        <th>Teléfono</th>
                        <th>Estación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($automoviles as $automovil)
                        <tr>
                            <td>{{ $automovil->PLA_VEH }}</td>
                            <td>{{ $automovil->TIP_VEH }}</td>
                            <td>{{ $automovil->MOD_VEH }}</td>
                            <td>{{ $automovil->NOM_PROP ?: '—' }}</td>
                            <td>{{ $automovil->TEL_PROP ?: '—' }}</td>
                            <td>{{ $automovil->estacion?->NOM_EST ?: 'Sin asignar' }}</td>
                            <td>
                                <div class="actions">
                                    <a class="button secondary" href="{{ route('automoviles.show', $automovil->COD_VEH) }}">Ver</a>
                                    @if ($esAdministrador)
                                        <a class="button" href="{{ route('automoviles.edit', $automovil->COD_VEH) }}">Editar</a>
                                        <form method="POST" action="{{ route('automoviles.destroy', $automovil->COD_VEH) }}" onsubmit="return confirm('¿Eliminar este automóvil?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="button danger" type="submit">Eliminar</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted">No hay automóviles asignados para mostrar.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($automoviles->hasPages())
            <div class="pagination">
                @if ($automoviles->onFirstPage())
                    <span class="muted">Anterior</span>
                @else
                    <a href="{{ $automoviles->previousPageUrl() }}">Anterior</a>
                @endif
                <span class="muted">Página {{ $automoviles->currentPage() }} de {{ $automoviles->lastPage() }}</span>
                @if ($automoviles->hasMorePages())
                    <a href="{{ $automoviles->nextPageUrl() }}">Siguiente</a>
                @else
                    <span class="muted">Siguiente</span>
                @endif
            </div>
        @endif
    </section>
@endsection
