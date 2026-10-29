<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('usuario', function (Blueprint $table) {
            $table->foreign('COD_ROL')->references('COD_ROL')->on('rol');
            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
        });
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            return;
        }

        Schema::table('usuario', function (Blueprint $table) {
            $table->dropForeign(['COD_ROL']);
            $table->dropForeign(['COD_EST']);
        });
    }
};
