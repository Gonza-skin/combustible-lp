<?php

namespace App\Http\Controllers;

use App\Models\Estacion;

class HomeController extends Controller
{
    public function index()
    {
        $estaciones = Estacion::query()
            ->where('ACT_EST', 1)
            ->orderBy('NOM_EST')
            ->get();

        $estacionesConCoordenadas = $estaciones
            ->filter(fn (Estacion $estacion) => $estacion->LAT_EST !== null && $estacion->LON_EST !== null)
            ->map(fn (Estacion $estacion) => [
                'nombre' => $estacion->NOM_EST,
                'direccion' => $estacion->DIR_EST,
                'estado' => $estacion->ESD_EST,
                'lat' => (float) $estacion->LAT_EST,
                'lng' => (float) $estacion->LON_EST,
            ])
            ->values();

        return view('index', [
            'estaciones' => $estaciones,
            'estacionesConCoordenadas' => $estacionesConCoordenadas,
            'estacionesDisponibles' => $estaciones->where('ESD_EST', 'normal')->count(),
        ]);
    }
}