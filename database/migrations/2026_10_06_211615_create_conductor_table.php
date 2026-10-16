<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conductor', function (Blueprint $table) {
            $table->increments('COD_CON');
            $table->unsignedInteger('COD_USU')->unique();
            $table->string('NOM_CON', 150);
            $table->string('CI_CON', 20)->unique();
            $table->string('LIC_CON', 20);
            $table->string('CAT_LIC', 10)->nullable();
            $table->char('TEL_CON', 9)->nullable();
            $table->boolean('ACT_CON')->default(true);

            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conductor');
    }
};
