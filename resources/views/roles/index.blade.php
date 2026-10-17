<x-layouts.app title="Roles">
    <x-page-header titulo="Roles" subtitulo="Roles del sistema y cuántos usuarios tiene cada uno" />

    <x-card>
        <x-table>
            <x-slot:head>
                <th>Rol</th>
                <th>Código</th>
                <th>Usuarios</th>
            </x-slot:head>

            @forelse ($roles as $rol)
                <tr>
                    <td>{{ $rol->nombre }}</td>
                    <td class="sec">{{ $rol->DES_ROL }}</td>
                    <td>{{ $rol->usuarios_count }}</td>
                </tr>
            @empty
                <tr><td colspan="3" class="vacio">No hay roles registrados.</td></tr>
            @endforelse
        </x-table>
    </x-card>
</x-layouts.app>