<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuario', function (Blueprint $table) {
            $table->increments('COD_USU');
            $table->string('NOM_USU', 150);
            $table->string('EML_USU', 150)->unique();
            $table->string('PAS_USU', 255);
            $table->unsignedInteger('COD_ROL');
            $table->unsignedInteger('COD_EST')->nullable();
            $table->boolean('ACT_USU')->default(true);
            $table->timestamp('FEC_USU')->useCurrent();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
