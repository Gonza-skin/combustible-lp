<x-layouts.app title="Estaciones">
    @php
        $esAdmin = auth()->user()?->tieneRol('administrador') ?? false;
        $textos = ['normal' => 'Normal', 'nivel_bajo' => 'Nivel bajo', 'sin_combustible' => 'Sin combustible'];
    @endphp

    <x-page-header titulo="Estaciones" subtitulo="Estaciones de servicio del Municipio de La Paz" />

    @if ($esAdmin)
        <p><a href="{{ route('estaciones.create') }}">+ Nueva estación</a></p>
    @endif

    <table>
        <tr>
            <th>Nombre</th><th>Zona</th><th>Dirección</th>
            <th>Mín</th><th>Máx</th><th>Estado</th><th>Activa</th>
            @if ($esAdmin)<th>Acciones</th>@endif
        </tr>
        @forelse ($estaciones as $e)
            <tr>
                <td>{{ $e->NOM_EST }}</td>
                <td>{{ $e->ZON_EST }}</td>
                <td>{{ $e->DIR_EST }}</td>
                <td>{{ $e->MIN_EST }}</td>
                <td>{{ $e->MAX_EST }}</td>
                <td>{{ $textos[$e->ESD_EST] ?? $e->ESD_EST }}</td>
                <td>{{ $e->ACT_EST ? 'Sí' : 'No' }}</td>
                @if ($esAdmin)
                    <td>
                        <a href="{{ route('estaciones.edit', $e) }}">Editar</a>
                        <form action="{{ route('estaciones.destroy', $e) }}" method="POST" style="display:inline"
                              onsubmit="return confirm('¿Eliminar esta estación?')">
                            @csrf @method('DELETE')
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                @endif
            </tr>
        @empty
            <tr><td colspan="{{ $esAdmin ? 8 : 7 }}">No hay estaciones registradas.</td></tr>
        @endforelse
    </table>
</x-layouts.app>