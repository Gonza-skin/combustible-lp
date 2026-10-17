<?php

namespace Database\Seeders;

<<<<<<< HEAD
use App\Models\Usuario;
=======
>>>>>>> main
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
<<<<<<< HEAD
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'EML_USU' => 'ciudadano@gmail.com',
                'NOM_USU' => 'Juan Ciudadano',
                'COD_ROL' => 1, // ciudadano
            ],
            [
                'EML_USU' => 'encargado@gmail.com',
                'NOM_USU' => 'Pedro Encargado',
                'COD_ROL' => 2, // encargado_estacion
            ],
            [
                'EML_USU' => 'subadmin@gmail.com',
                'NOM_USU' => 'Ana Sub Administrador',
                'COD_ROL' => 3, // sub_administrador
            ],
            [
                'EML_USU' => 'conductor@gmail.com',
                'NOM_USU' => 'Luis Conductor',
                'COD_ROL' => 4, // conductor_cisterna
            ],
            [
                'EML_USU' => 'admin@gmail.com',
                'NOM_USU' => 'Carlos Administrador',
                'COD_ROL' => 5, // administrador
            ],
            [
                'EML_USU' => 'deposito@gmail.com',
                'NOM_USU' => 'Maria Admin Deposito',
                'COD_ROL' => 6, // administrador_deposito
            ],
            [
                'EML_USU' => 'supervisor@gmail.com',
                'NOM_USU' => 'Jorge Supervisor',
                'COD_ROL' => 7, // supervisor
            ],
            [
                'EML_USU' => 'adminsistema@gmail.com',
                'NOM_USU' => 'Laura Admin Sistema',
                'COD_ROL' => 8, // administrador_sistema
            ],
        ];

        // Contraseña para todos
        $password = Hash::make('123qwerty');

        foreach ($usuarios as $usuario) {
            Usuario::updateOrCreate(
                ['EML_USU' => $usuario['EML_USU']],
                [
                    'NOM_USU' => $usuario['NOM_USU'],
                    'PAS_USU' => $password,
                    'COD_ROL' => $usuario['COD_ROL'],
                    'COD_EST' => null,
                    'ACT_USU' => 1,
                ]
            );
        }
=======
    public function run(): void
    {
        // Crea las cuentas de prueba (una por rol). Se puede correr muchas veces sin duplicar.
        $this->call(UsuariosDemoSeeder::class);
>>>>>>> main
    }
}