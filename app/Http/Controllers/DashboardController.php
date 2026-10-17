<?php

namespace App\Http\Controllers;

use App\Models\Estacion;
use App\Models\Cisterna;
use App\Models\Incidencia;
use App\Models\TipoCombustible;

class DashboardController extends Controller
{
    public function index()
    {
        $estacionesActivas   = Estacion::where('ACT_EST', 1)->count();
        $estacionesNivelBajo = Estacion::where('ESD_EST', 'nivel_bajo')->count();
        $cisternasEnTransito = Cisterna::where('ESD_CIS', 'en_transito')->count();
        $incidenciasAbiertas = Incidencia::whereNotIn('ESD_INC', ['cerrada'])->count();

        // Nombres de los combustibles (Gasolina, Diesel)
        $tiposCombustible = TipoCombustible::orderBy('COD_COM')->pluck('DES_COM');

        // Estaciones activas con coordenadas, para los marcadores del mapa
        $estaciones = Estacion::where('ACT_EST', 1)
            ->whereNotNull('LAT_EST')
            ->whereNotNull('LON_EST')
            ->get()
            ->map(fn ($e) => [
                'nombre'    => $e->NOM_EST,
                'direccion' => $e->DIR_EST,
                'estado'    => $e->ESD_EST,
                'lat'       => (float) $e->LAT_EST,
                'lng'       => (float) $e->LON_EST,
            ])
            ->values();

        return view('dashboard', compact(
            'estacionesActivas',
            'estacionesNivelBajo',
            'cisternasEnTransito',
            'incidenciasAbiertas',
            'tiposCombustible',
            'estaciones'
        ));
    }
}