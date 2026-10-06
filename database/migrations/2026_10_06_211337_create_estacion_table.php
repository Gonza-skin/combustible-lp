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
        Schema::create('estacion', function (Blueprint $table) {
            $table->increments('COD_EST');
            $table->string('NOM_EST', 120);
            $table->string('DIR_EST', 200)->nullable();
            $table->string('ZON_EST', 80)->nullable();
            $table->decimal('LAT_EST', 10, 7)->nullable();
            $table->decimal('LON_EST', 10, 7)->nullable();
            $table->decimal('MIN_EST', 10, 2)->default(0);
            $table->decimal('MAX_EST', 10, 2)->default(0);
            $table->integer('TPT_EST')->default(60);
            $table->string('ESD_EST', 20)->default('sin_combustible');
            $table->boolean('ACT_EST')->default(true);
            $table->timestamp('FEC_EST')->useCurrent();
            $table->check("ESD_EST IN ('normal','nivel_bajo','sin_combustible')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estacion');
    }
};
