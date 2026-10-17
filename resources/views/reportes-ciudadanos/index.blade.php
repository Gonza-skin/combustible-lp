@extends('modulos.layout')

@section('title', 'Reportes ciudadanos')
@section('heading', 'Reportes ciudadanos')

@section('content')
    <section class="panel">
        <h2>Reportes de ciudadanos</h2>
        <p class="muted">Listado de reportes enviados por los ciudadanos para las estaciones.</p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Estación</th>
                        <th>Ciudadano</th>
                        <th>Estado de la fila</th>
                        <th>Disponibilidad</th>
                        <th>Espera estimada (min)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($reportes as $reporte)
                        <tr>
                            <td>{{ $reporte->FEC_REP }}</td>
                            <td>{{ $reporte->ESTACION }}</td>
                            <td>{{ $reporte->CIUDADANO ?: 'Sin usuario asociado' }}</td>
                            <td>{{ $reporte->FIL_REP ? ucfirst(str_replace('_', ' ', $reporte->FIL_REP)) : 'Sin información' }}</td>
                            <td>{{ $reporte->DIS_REP ? ucfirst(str_replace('_', ' ', $reporte->DIS_REP)) : 'Sin información' }}</td>
                            <td>{{ $reporte->TES_REP ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="muted">Todavía no hay reportes ciudadanos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($reportes->hasPages())
            <div class="pagination">
                @if ($reportes->onFirstPage())
                    <span class="muted">Anterior</span>
                @else
                    <a href="{{ $reportes->previousPageUrl() }}">Anterior</a>
                @endif
                <span class="muted">Página {{ $reportes->currentPage() }} de {{ $reportes->lastPage() }}</span>
                @if ($reportes->hasMorePages())
                    <a href="{{ $reportes->nextPageUrl() }}">Siguiente</a>
                @else
                    <span class="muted">Siguiente</span>
                @endif
            </div>
        @endif
    </section>
@endsection
