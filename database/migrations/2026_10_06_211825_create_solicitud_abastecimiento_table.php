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
        Schema::create('solicitud_abastecimiento', function (Blueprint $table) {
            $table->increments('COD_SOL');
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_COM');
            $table->decimal('CAN_SOL', 10, 2);
            $table->string('ESD_SOL', 20)->default('pendiente');
            $table->unsignedInteger('COD_USU');
            $table->timestamp('FEC_SOL')->useCurrent();

            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
        });

        $this->addCheckConstraints('solicitud_abastecimiento', [
            'chk_sol_can' => 'CAN_SOL > 0',
            'chk_sol_esd' => "ESD_SOL IN ('pendiente','atendida','cancelada')",
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_abastecimiento');
    }
};
