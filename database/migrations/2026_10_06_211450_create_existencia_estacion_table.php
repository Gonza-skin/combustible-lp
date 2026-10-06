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
        Schema::create('existencia_estacion', function (Blueprint $table) {
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_COM');
            $table->decimal('CAN_EXI', 10, 2)->default(0);
            $table->timestamp('FEC_EXI')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['COD_EST', 'COD_COM']);
            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->check('CAN_EXI >= 0');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('existencia_estacion');
    }
};
