@props(['action', 'mensaje' => '¿Seguro que quieres continuar?'])
<form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($mensaje))">
    @csrf
    @method('DELETE')
    <x-button variant="outline" class="btn--sm">{{ $slot }}</x-button>
</form>