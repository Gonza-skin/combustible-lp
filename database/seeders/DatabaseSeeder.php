<?php

namespace Database\Seeders;

use App\Models\Usuario;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('rol')->updateOrInsert(
            ['COD_ROL' => 5],
            ['DES_ROL' => 'administrador']
        );

        Usuario::updateOrCreate(
            ['EML_USU' => 'admin@gmail.com'],
            [
                'NOM_USU' => 'Administrados Admin Sistema',
                'PAS_USU' => Hash::make('123qwerty'),
                'COD_ROL' => 5,
                'COD_EST' => null,
                'ACT_USU' => 1,
            ]
        );
    }
}
