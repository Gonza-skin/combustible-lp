<?php

use App\Support\AddsCheckConstraints;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use AddsCheckConstraints;

    public function up(): void
    {
        Schema::create('alerta', function (Blueprint $table) {
            $table->increments('COD_ALE');
            $table->string('TIP_ALE', 30);
            $table->string('MEN_ALE', 255);
            $table->string('NIV_ALE', 10)->default('media');
            $table->unsignedInteger('COD_EST')->nullable();
            $table->unsignedInteger('COD_ABA')->nullable();
            $table->unsignedInteger('COD_TAN')->nullable();
            $table->unsignedInteger('COD_INC')->nullable();
            $table->boolean('LEI_ALE')->default(false);
            $table->timestamp('FEC_ALE')->useCurrent();

            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_ABA')->references('COD_ABA')->on('abastecimiento');
            $table->foreign('COD_TAN')->references('COD_TAN')->on('tanque_almacenamiento');
            $table->foreign('COD_INC')->references('COD_INC')->on('incidencia');
        });

        $this->addCheckConstraints('alerta', [
            'chk_ale_tip' => "TIP_ALE IN ('existencia_minima','cisterna_retrasada','diferencia_combustible','tanque_critico','otro')",
            'chk_ale_niv' => "NIV_ALE IN ('baja','media','alta')",
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('alerta');
    }
};
