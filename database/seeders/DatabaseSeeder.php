<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Crea las cuentas de prueba (una por rol). Se puede correr muchas veces sin duplicar.
        $this->call(UsuariosDemoSeeder::class);
    }
}