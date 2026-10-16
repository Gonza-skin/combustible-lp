<x-layouts.app title="Editar tipo de combustible">
    <x-page-header titulo="Editar tipo de combustible" :subtitulo="$tipo->DES_COM" />
    <x-card>
        @include('tipos-combustible._form', ['accion' => route('tipos-combustible.update', $tipo)])
    </x-card>
</x-layouts.app>