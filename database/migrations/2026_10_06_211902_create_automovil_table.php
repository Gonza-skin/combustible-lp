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
        Schema::create('automovil', function (Blueprint $table) {
            $table->increments('COD_VEH');
            $table->string('PLA_VEH', 15)->unique();
            $table->string('TIP_VEH', 20)->nullable();
            $table->string('MOD_VEH', 60)->nullable();
            $table->string('NOM_PROP', 150)->nullable();
            $table->char('TEL_PROP', 9)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('automovil');
    }
};
