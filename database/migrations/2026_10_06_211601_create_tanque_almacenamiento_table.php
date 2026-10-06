<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tanque_almacenamiento', function (Blueprint $table) {
            $table->increments('COD_TAN');
            $table->unsignedInteger('COD_PLA');
            $table->unsignedInteger('COD_COM');
            $table->string('NOM_TAN', 60);
            $table->decimal('CAP_TAN', 10, 2);
            $table->decimal('NIV_TAN', 10, 2)->default(0);
            $table->string('ESD_TAN', 20)->default('operativo');

            $table->foreign('COD_PLA')->references('COD_PLA')->on('planta_deposito');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->check('CAP_TAN > 0');
            $table->check('NIV_TAN >= 0 AND NIV_TAN <= CAP_TAN');
            $table->check("ESD_TAN IN ('operativo','critico','mantenimiento','inactivo')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tanque_almacenamiento');
    }
};
