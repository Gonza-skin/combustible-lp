@csrf

@if ($errors->any())
    <div class="aviso-error">
        <ul style="margin:0;padding-left:18px">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="campo">
    <label>Nombre *</label>
    <input type="text" name="NOM_EST" value="{{ old('NOM_EST', $estacion->NOM_EST ?? '') }}" required>
</div>

<div class="campo">
    <label>Dirección</label>
    <input type="text" name="DIR_EST" value="{{ old('DIR_EST', $estacion->DIR_EST ?? '') }}">
</div>

<div class="campo">
    <label>Zona</label>
    <input type="text" name="ZON_EST" value="{{ old('ZON_EST', $estacion->ZON_EST ?? '') }}">
</div>

<div class="campo">
    <label>Latitud</label>
    <input type="text" name="LAT_EST" value="{{ old('LAT_EST', $estacion->LAT_EST ?? '') }}">
</div>

<div class="campo">
    <label>Longitud</label>
    <input type="text" name="LON_EST" value="{{ old('LON_EST', $estacion->LON_EST ?? '') }}">
</div>

<div class="campo">
    <label>Mínimo *</label>
    <input type="number" step="0.01" name="MIN_EST" value="{{ old('MIN_EST', $estacion->MIN_EST ?? 0) }}" required>
</div>

<div class="campo">
    <label>Máximo *</label>
    <input type="number" step="0.01" name="MAX_EST" value="{{ old('MAX_EST', $estacion->MAX_EST ?? 0) }}" required>
</div>

<div class="campo">
    <label>Tiempo de tránsito (minutos) *</label>
    <input type="number" name="TPT_EST" value="{{ old('TPT_EST', $estacion->TPT_EST ?? 60) }}" required>
</div>

<div class="campo">
    <label>Estado *</label>
    <select name="ESD_EST">
        @foreach (['normal' => 'Normal', 'nivel_bajo' => 'Nivel bajo', 'sin_combustible' => 'Sin combustible'] as $valor => $texto)
            <option value="{{ $valor }}" @selected(old('ESD_EST', $estacion->ESD_EST ?? 'sin_combustible') === $valor)>{{ $texto }}</option>
        @endforeach
    </select>
</div>

<div class="campo">
    <label style="font-weight:normal">
        <input type="checkbox" name="ACT_EST" value="1" @checked(old('ACT_EST', $estacion->ACT_EST ?? 1))>
        Estación activa
    </label>
</div>