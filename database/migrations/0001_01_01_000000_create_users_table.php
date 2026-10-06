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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('COD_USU');
            $table->string('NOM_USU', 150);
            $table->string('EML_USU', 150)->unique();
            $table->string('PAS_USU', 255);
            $table->unsignedInteger('COD_ROL');
            $table->unsignedInteger('COD_EST')->nullable();
            $table->boolean('ACT_USU')->default(true);
            $table->timestamp('FEC_USU')->useCurrent();

            $table->foreign('COD_ROL')->references('COD_ROL')->on('rol');
            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');

            //$table->id();
            //$table->string('name');
            //$table->string('email')->unique();
            //$table->timestamp('email_verified_at')->nullable();
            //$table->string('password');
            //$table->rememberToken();
            //$table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
