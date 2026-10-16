<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('planta_deposito', function (Blueprint $table) {
            $table->increments('COD_PLA');
            $table->string('NOM_PLA', 120);
            $table->string('UBI_PLA', 200)->nullable();
            $table->boolean('ACT_PLA')->default(true);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('planta_deposito');
    }
};
