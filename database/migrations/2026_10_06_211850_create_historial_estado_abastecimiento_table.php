<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('historial_estado_abastecimiento', function (Blueprint $table) {
            $table->bigIncrements('COD_HIS');
            $table->unsignedInteger('COD_ABA');
            $table->string('ESD_HIS', 20);
            $table->unsignedInteger('COD_USU')->nullable();
            $table->string('OBS_HIS', 255)->nullable();
            $table->timestamp('FEC_HIS')->useCurrent();

            $table->foreign('COD_ABA')->references('COD_ABA')->on('abastecimiento');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->check("ESD_HIS IN ('pendiente','asignado','en_transito','entregado','cerrado','cancelado')", 'chk_his_esd');
            $table->index(['COD_ABA', 'FEC_HIS'], 'idx_his_aba');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_estado_abastecimiento');
    }
};
