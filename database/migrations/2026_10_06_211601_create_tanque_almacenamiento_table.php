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
        Schema::create('tanque_almacenamiento', function (Blueprint $table) {
            $table->increments('COD_TAN');
            $table->unsignedInteger('COD_PLA');
            $table->unsignedInteger('COD_COM');
            $table->string('NOM_TAN', 60);
            $table->decimal('CAP_TAN', 10, 2);
            $table->decimal('NIV_TAN', 10, 2)->default(0);
            $table->string('ESD_TAN', 20)->default('operativo');

            $table->foreign('COD_PLA')->references('COD_PLA')->on('planta_deposito');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
        });

        $this->addCheckConstraints('tanque_almacenamiento', [
            'chk_tan_cap' => 'CAP_TAN > 0',
            'chk_tan_niv' => 'NIV_TAN >= 0 AND NIV_TAN <= CAP_TAN',
            'chk_tan_esd' => "ESD_TAN IN ('operativo','critico','mantenimiento','inactivo')",
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('tanque_almacenamiento');
    }
};
