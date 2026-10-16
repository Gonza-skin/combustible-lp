<x-layouts.app title="Inicio">
    <x-page-header titulo="Panel de control"
                   subtitulo="Sistema de Control y Gestión de Abastecimiento de Combustible - Municipio de La Paz" />

    <div class="stats">
        <x-stat :valor="$estacionesActivas" texto="Estaciones activas" />
        <x-stat :valor="$estacionesNivelBajo" texto="Estaciones con nivel bajo" alerta />
        <x-stat :valor="$cisternasEnTransito" texto="Cisternas en tránsito" />
        <x-stat :valor="$incidenciasAbiertas" texto="Incidencias abiertas" alerta />
    </div>
</x-layouts.app>