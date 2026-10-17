<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('automovil', function (Blueprint $table) {
            $table->unsignedInteger('COD_EST')->nullable()->after('TEL_PROP');
            $table->foreign('COD_EST')
                ->references('COD_EST')
                ->on('estacion')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('automovil', function (Blueprint $table) {
            $table->dropForeign(['COD_EST']);
            $table->dropColumn('COD_EST');
        });
    }
};
