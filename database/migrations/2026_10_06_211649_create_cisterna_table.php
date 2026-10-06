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
        Schema::create('cisterna', function (Blueprint $table) {
            $table->increments('COD_CIS');
            $table->string('PLC_CIS', 20)->unique();
            $table->decimal('CAP_CIS', 10, 2);
            $table->unsignedInteger('COD_COM');
            $table->unsignedInteger('COD_CON')->nullable();
            $table->string('ESD_CIS', 20)->default('disponible');

            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_CON')->references('COD_CON')->on('conductor');
            $table->check('CAP_CIS > 0');
            $table->check("ESD_CIS IN ('disponible','asignada','en_transito','mantenimiento')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cisterna');
    }
};
