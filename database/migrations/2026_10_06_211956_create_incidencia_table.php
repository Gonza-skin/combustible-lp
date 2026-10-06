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
       Schema::create('incidencia', function (Blueprint $table) {
            $table->increments('COD_INC');
            $table->string('TIP_INC', 30);
            $table->unsignedInteger('COD_ABA')->nullable();
            $table->unsignedInteger('COD_TAN')->nullable();
            $table->text('DES_INC')->nullable();
            $table->string('ESD_INC', 20)->default('detectada');
            $table->unsignedInteger('COD_USU')->nullable();
            $table->text('ACC_INC')->nullable();
            $table->timestamp('FEC_INC')->useCurrent();
            $table->dateTime('FRS_INC')->nullable();

            $table->foreign('COD_ABA')->references('COD_ABA')->on('abastecimiento');
            $table->foreign('COD_TAN')->references('COD_TAN')->on('tanque_almacenamiento');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->check("TIP_INC IN ('retraso','diferencia_combustible','almacenamiento','otro')");
            $table->check("ESD_INC IN ('detectada','en_revision','resuelta','cerrada')");
            $table->check("ESD_INC NOT IN ('resuelta','cerrada') OR FRS_INC IS NOT NULL");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidencia');
    }
};
