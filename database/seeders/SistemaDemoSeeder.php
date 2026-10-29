<?php

namespace Database\Seeders;

use App\Models\Estacion;
use App\Models\Usuario;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SistemaDemoSeeder extends Seeder
{
    public function run(): void
    {
        $estacion = Estacion::updateOrCreate(
            ['NOM_EST' => 'Estación de demostración'],
            [
                'DIR_EST' => 'Centro, La Paz',
                'ZON_EST' => 'Centro',
                'LAT_EST' => -16.5000,
                'LON_EST' => -68.1500,
                'MIN_EST' => 200,
                'MAX_EST' => 10000,
                'TPT_EST' => 60,
                'ESD_EST' => 'normal',
                'ACT_EST' => true,
            ]
        );

        DB::table('tipo_combustible')->orderBy('COD_COM')->each(function (object $combustible) use ($estacion): void {
            DB::table('existencia_estacion')->insertOrIgnore([
                'COD_EST' => $estacion->COD_EST,
                'COD_COM' => $combustible->COD_COM,
                'CAN_EXI' => 5000,
            ]);
        });

        DB::table('automovil')->insertOrIgnore([
            'PLA_VEH' => 'DEMO-001',
            'TIP_VEH' => 'Demostración',
            'MOD_VEH' => 'Vehículo demo',
            'NOM_PROP' => 'Usuario de demostración',
            'TEL_PROP' => '000000000',
            'COD_EST' => $estacion->COD_EST,
        ]);

        Usuario::where('EML_USU', 'encargado_estacion@combustible.test')
            ->update(['COD_EST' => $estacion->COD_EST]);
    }
}
