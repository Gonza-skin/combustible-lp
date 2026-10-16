@if (session('exito'))
    <div class="alerta alerta--exito" role="status">{{ session('exito') }}</div>
@endif
@if (session('error'))
    <div class="alerta alerta--error" role="alert">{{ session('error') }}</div>
@endif
@if ($errors->any())
    <div class="alerta alerta--error" role="alert">Revisa los campos marcados: hay datos que corregir.</div>
@endif