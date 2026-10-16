<x-layouts.app title="Tipos de combustible">
    <x-page-header titulo="Tipos de combustible" subtitulo="Combustibles que maneja el sistema" />

    <x-card>
        <x-table>
            <x-slot:head>
                <th>Código</th>
                <th>Combustible</th>
                <th></th>
            </x-slot:head>

            @forelse ($tipos as $tipo)
                <tr>
                    <td class="sec">{{ $tipo->COD_COM }}</td>
                    <td>{{ $tipo->DES_COM }}</td>
                    <td class="acciones-celda">
                        <x-button :href="route('tipos-combustible.edit', $tipo)" variant="outline" class="btn--sm">Editar</x-button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="3" class="vacio">No hay tipos de combustible registrados.</td></tr>
            @endforelse
        </x-table>
    </x-card>
</x-layouts.app>