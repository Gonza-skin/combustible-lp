<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SistemaCombustibleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('rol')->insert([
            ['DES_ROL' => 'ciudadano'],
            ['DES_ROL' => 'encargado_estacion'],
            ['DES_ROL' => 'sub_administrador'],
            ['DES_ROL' => 'conductor_cisterna'],
            ['DES_ROL' => 'administrador'],
            ['DES_ROL' => 'administrador_deposito'],
            ['DES_ROL' => 'supervisor'],
            ['DES_ROL' => 'administrador_sistema'],
        ]);

        DB::table('tipo_combustible')->insert([
            ['DES_COM' => 'Gasolina'],
            ['DES_COM' => 'Diesel'],
        ]);
    }
}
