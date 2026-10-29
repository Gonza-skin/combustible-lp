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
        Schema::create('mantenimiento_cisterna', function (Blueprint $table) {
            $table->increments('COD_MAN');
            $table->unsignedInteger('COD_CIS');
            $table->string('TIP_MAN', 20);
            $table->text('DES_MAN')->nullable();
            $table->date('FEC_MAN');

            $table->foreign('COD_CIS')->references('COD_CIS')->on('cisterna');
        });

        $this->addCheckConstraints('mantenimiento_cisterna', [
            'chk_man_tip' => "TIP_MAN IN ('preventivo','correctivo','documentacion')",
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('mantenimiento_cisterna');
    }
};
