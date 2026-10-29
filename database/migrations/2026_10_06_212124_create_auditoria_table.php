<?php

use App\Support\AddsCheckConstraints;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use AddsCheckConstraints;

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
        });

        $this->addCheckConstraints('auditoria', [
            'chk_aud_acc' => "ACC_AUD IN ('INSERT','UPDATE','DELETE')",
        ]);

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared(<<<'SQL'
                CREATE FUNCTION impedir_cambios_auditoria() RETURNS TRIGGER AS $$
                BEGIN
                    RAISE EXCEPTION 'El registro de auditoria no puede modificarse ni eliminarse';
                END;
                $$ LANGUAGE plpgsql
            SQL);
            DB::unprepared('CREATE TRIGGER trg_auditoria_bu BEFORE UPDATE OR DELETE ON auditoria FOR EACH ROW EXECUTE FUNCTION impedir_cambios_auditoria()');

            return;
        }

        DB::unprepared("
            CREATE TRIGGER trg_auditoria_bu BEFORE UPDATE ON auditoria
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El registro de auditoria no puede modificarse';
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER trg_auditoria_bd BEFORE DELETE ON auditoria
            FOR EACH ROW
            BEGIN
                SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'El registro de auditoria no puede eliminarse';
            END;
        ");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_auditoria_bu ON auditoria');
            DB::unprepared('DROP FUNCTION IF EXISTS impedir_cambios_auditoria()');
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_auditoria_bd');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_auditoria_bu');
        Schema::dropIfExists('auditoria');
    }
};
