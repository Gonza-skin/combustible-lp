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
        Schema::create('venta_combustible', function (Blueprint $table) {
            $table->bigIncrements('COD_VEN');
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_VEH');
            $table->unsignedInteger('COD_COM');
            $table->decimal('CAN_VEN', 10, 2);
            $table->decimal('PRE_VEN', 10, 2);
            $table->decimal('VAL_VEN', 10, 2)
                ->storedAs('CAN_VEN * PRE_VEN');
            $table->unsignedInteger('COD_USU');
            $table->timestamp('FEC_VEN')->useCurrent();

            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_VEH')->references('COD_VEH')->on('automovil');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->check('CAN_VEN > 0');
            $table->check('PRE_VEN > 0');
            $table->index(['COD_VEH', 'FEC_VEN'], 'idx_ven_veh_fec');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('venta_combustible');
    }
};
