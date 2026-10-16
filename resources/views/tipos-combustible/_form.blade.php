<form method="POST" action="{{ $accion }}" class="formulario">
    @csrf
    @if ($tipo->exists) @method('PUT') @endif

    <x-input name="DES_COM" label="Nombre del combustible" :value="$tipo->DES_COM" maxlength="30" required
             hint="Ejemplo: Gasolina, Diesel, GNV" />

    <div class="acciones">
        <x-button variant="primary">Guardar</x-button>
        <x-button :href="route('tipos-combustible.index')" variant="outline">Cancelar</x-button>
    </div>
</form>