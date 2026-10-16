<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tipo_combustible', function (Blueprint $table) {
            $table->increments('COD_COM');
            $table->string('DES_COM', 30)->unique();
        });

        DB::table('tipo_combustible')->insert([
            ['DES_COM' => 'Gasolina'],
            ['DES_COM' => 'Diesel']
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tipo_combustible');
    }
};
