@php($grupoActual = null)
@foreach (config('menu', []) as $item)
    @continue(! \Illuminate\Support\Facades\Route::has($item['ruta']))
    @continue(! auth()->user()->tieneRol(...($item['roles'] ?? [])))
    @if (($item['grupo'] ?? null) !== $grupoActual)
        @php($grupoActual = $item['grupo'] ?? null)
        @if ($grupoActual)
            <div class="menu__titulo">{{ $grupoActual }}</div>
        @endif
    @endif
    <a href="{{ route($item['ruta']) }}"
       @class(['activo' => request()->routeIs(\Illuminate\Support\Str::beforeLast($item['ruta'], '.').'.*') || request()->routeIs($item['ruta'])])>
        {{ $item['titulo'] }}
    </a>
@endforeach
