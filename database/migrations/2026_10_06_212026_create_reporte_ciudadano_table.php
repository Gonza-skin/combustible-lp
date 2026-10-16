<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_ciudadano', function (Blueprint $table) {
            $table->increments('COD_REP');
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_USU')->nullable();
            $table->string('FIL_REP', 20)->nullable();
            $table->string('DIS_REP', 20)->nullable();
            $table->integer('TES_REP')->nullable();
            $table->timestamp('FEC_REP')->useCurrent();

            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->check("FIL_REP IN ('sin_fila','corta','moderada','larga')", 'chk_rep_fil');
            $table->check("DIS_REP IN ('disponible','poco_stock','agotado')", 'chk_rep_dis');
            $table->index(['COD_EST', 'FEC_REP'], 'idx_rep_est_fec');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_ciudadano');
    }
};
