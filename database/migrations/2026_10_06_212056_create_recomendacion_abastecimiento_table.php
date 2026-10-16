<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recomendacion_abastecimiento', function (Blueprint $table) {
            $table->increments('COD_REC');
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_COM');
            $table->decimal('POR_REC', 5, 2)->nullable();
            $table->decimal('CPD_REC', 10, 2)->nullable();
            $table->decimal('CSU_REC', 10, 2)->nullable();
            $table->timestamp('FEC_REC')->useCurrent();

            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recomendacion_abastecimiento');
    }
};
