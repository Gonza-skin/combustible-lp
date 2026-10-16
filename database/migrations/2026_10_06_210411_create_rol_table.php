<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rol', function (Blueprint $table) {
            $table->increments('COD_ROL');
            $table->string('DES_ROL', 50)->unique();
        });

        DB::table('rol')->insert([
            ['DES_ROL' => 'ciudadano'],
            ['DES_ROL' => 'encargado_estacion'],
            ['DES_ROL' => 'sub_administrador'],
            ['DES_ROL' => 'conductor_cisterna'],
            ['DES_ROL' => 'administrador'],
            ['DES_ROL' => 'administrador_deposito'],
            ['DES_ROL' => 'supervisor'],
            ['DES_ROL' => 'administrador_sistema']
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('rol');
    }
};
