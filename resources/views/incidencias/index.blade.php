@extends('modulos.layout')

@section('title', 'Incidencias')
@section('heading', 'Incidencias')

@section('content')
    <section class="panel">
        <div class="actions" style="justify-content: space-between; margin-bottom: 18px">
            <h2>Incidencias registradas</h2>
            <a class="button" href="{{ route('incidencias.create') }}">Registrar incidencia</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th>Abastecimiento</th>
                        <th>Tanque</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($incidencias as $incidencia)
                        <tr>
                            <td>{{ $incidencia->FEC_INC }}</td>
                            <td>{{ str_replace('_', ' ', $incidencia->TIP_INC) }}</td>
                            <td>{{ $incidencia->DES_INC ?: '—' }}</td>
                            <td>{{ str_replace('_', ' ', $incidencia->ESD_INC) }}</td>
                            <td>{{ $incidencia->COD_ABA ?: '—' }}</td>
                            <td>{{ $incidencia->COD_TAN ?: '—' }}</td>
                            <td>
                                <div class="actions">
                                    <a class="button secondary" href="{{ route('incidencias.show', $incidencia->COD_INC) }}">Ver</a>
                                    <a class="button" href="{{ route('incidencias.edit', $incidencia->COD_INC) }}">Editar</a>
                                    <form method="POST" action="{{ route('incidencias.destroy', $incidencia->COD_INC) }}" onsubmit="return confirm('¿Eliminar esta incidencia?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button danger" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted">No hay incidencias registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($incidencias->hasPages())
            <div class="pagination">
                @if ($incidencias->onFirstPage())
                    <span class="muted">Anterior</span>
                @else
                    <a href="{{ $incidencias->previousPageUrl() }}">Anterior</a>
                @endif
                <span class="muted">Página {{ $incidencias->currentPage() }} de {{ $incidencias->lastPage() }}</span>
                @if ($incidencias->hasMorePages())
                    <a href="{{ $incidencias->nextPageUrl() }}">Siguiente</a>
                @else
                    <span class="muted">Siguiente</span>
                @endif
            </div>
        @endif
    </section>
@endsection
