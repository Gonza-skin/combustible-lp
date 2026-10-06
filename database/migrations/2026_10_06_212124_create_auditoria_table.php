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
        Schema::create('auditoria', function (Blueprint $table) {
            $table->bigIncrements('COD_AUD');
            $table->unsignedInteger('COD_USU')->nullable();
            $table->string('TAB_AUD', 60);
            $table->string('ACC_AUD', 10);
            $table->string('REG_AUD', 50)->nullable();
            $table->json('VAN_AUD')->nullable();
            $table->json('VNU_AUD')->nullable();
            $table->timestamp('FEC_AUD')->useCurrent();

            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->check("ACC_AUD IN ('INSERT','UPDATE','DELETE')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auditoria');
    }
};
