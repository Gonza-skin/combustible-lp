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
        Schema::create('movimiento_existencia', function (Blueprint $table) {
            $table->bigIncrements('COD_MOV');
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_COM');
            $table->string('TIP_MOV', 10);
            $table->decimal('CAN_MOV', 10, 2);
            $table->unsignedInteger('COD_USU')->nullable();
            $table->string('REF_MOV', 150)->nullable();
            $table->timestamp('FEC_MOV')->useCurrent();

            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->index(['COD_EST', 'FEC_MOV'], 'idx_mov_est_fec');
        });

        $this->addCheckConstraints('movimiento_existencia', [
            'chk_mov_tip' => "TIP_MOV IN ('entrada','salida')",
            'chk_mov_can' => 'CAN_MOV > 0',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('movimiento_existencia');
    }
};
