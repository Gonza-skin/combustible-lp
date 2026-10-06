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
        Schema::create('abastecimiento', function (Blueprint $table) {
            $table->increments('COD_ABA');
            $table->unsignedInteger('COD_SOL')->unique();
            $table->unsignedInteger('COD_COM');
            $table->unsignedInteger('COD_CIS')->nullable();
            $table->decimal('CAN_ABA', 10, 2);
            $table->string('ESD_ABA', 20)->default('pendiente');
            $table->dateTime('HSA_ABA')->nullable();
            $table->dateTime('HEL_ABA')->nullable();
            $table->dateTime('HRL_ABA')->nullable();
            $table->boolean('RET_ABA')->nullable();
            $table->integer('MRE_ABA')->nullable();
            $table->decimal('CCA_ABA', 10, 2)->nullable();
            $table->decimal('CRE_ABA', 10, 2)->nullable();
            $table->decimal('DIF_ABA', 10, 2)
                ->storedAs("CASE WHEN CCA_ABA IS NULL OR CRE_ABA IS NULL THEN NULL ELSE CCA_ABA - CRE_ABA END");
            $table->unsignedInteger('COD_USU')->nullable();
            $table->timestamp('FEC_ABA')->useCurrent();

            $table->foreign('COD_SOL')->references('COD_SOL')->on('solicitud_abastecimiento');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_CIS')->references('COD_CIS')->on('cisterna');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->check('CAN_ABA > 0');
            $table->check("ESD_ABA IN ('pendiente','asignado','en_transito','entregado','cerrado','cancelado')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('abastecimiento');
    }
};
