@extends('modulos.layout')

@section('title', 'Alertas')
@section('heading', 'Alertas')

@section('content')
    <section class="panel">
        <h2>Alertas del sistema</h2>
        <p class="muted">Vista de solo lectura de alertas registradas.</p>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Tipo</th>
                        <th>Nivel</th>
                        <th>Mensaje</th>
                        <th>Estación</th>
                        <th>Incidencia</th>
                        <th>Leída</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($alertas as $alerta)
                        <tr>
                            <td>{{ $alerta->FEC_ALE }}</td>
                            <td>{{ str_replace('_', ' ', $alerta->TIP_ALE) }}</td>
                            <td>{{ ucfirst($alerta->NIV_ALE) }}</td>
                            <td>{{ $alerta->MEN_ALE }}</td>
                            <td>{{ $alerta->ESTACION ?: '—' }}</td>
                            <td>{{ $alerta->COD_INC ?: '—' }}</td>
                            <td>{{ $alerta->LEI_ALE ? 'Sí' : 'No' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="muted">No hay alertas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($alertas->hasPages())
            <div class="pagination">
                @if ($alertas->onFirstPage())
                    <span class="muted">Anterior</span>
                @else
                    <a href="{{ $alertas->previousPageUrl() }}">Anterior</a>
                @endif
                <span class="muted">Página {{ $alertas->currentPage() }} de {{ $alertas->lastPage() }}</span>
                @if ($alertas->hasMorePages())
                    <a href="{{ $alertas->nextPageUrl() }}">Siguiente</a>
                @else
                    <span class="muted">Siguiente</span>
                @endif
            </div>
        @endif
    </section>
@endsection
