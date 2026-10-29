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
        Schema::create('incidencia', function (Blueprint $table) {
            $table->increments('COD_INC');
            $table->string('TIP_INC', 30);
            $table->unsignedInteger('COD_ABA')->nullable();
            $table->unsignedInteger('COD_TAN')->nullable();
            $table->text('DES_INC')->nullable();
            $table->string('ESD_INC', 20)->default('detectada');
            $table->unsignedInteger('COD_USU')->nullable();
            $table->text('ACC_INC')->nullable();
            $table->timestamp('FEC_INC')->useCurrent();
            $table->dateTime('FRS_INC')->nullable();

            $table->foreign('COD_ABA')->references('COD_ABA')->on('abastecimiento');
            $table->foreign('COD_TAN')->references('COD_TAN')->on('tanque_almacenamiento');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
        });

        $this->addCheckConstraints('incidencia', [
            'chk_inc_tip' => "TIP_INC IN ('retraso','diferencia_combustible','almacenamiento','otro')",
            'chk_inc_esd' => "ESD_INC IN ('detectada','en_revision','resuelta','cerrada')",
            'chk_inc_frs' => "ESD_INC NOT IN ('resuelta','cerrada') OR FRS_INC IS NOT NULL",
        ]);

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared(<<<'SQL'
                CREATE FUNCTION validar_transicion_incidencia() RETURNS TRIGGER AS $$
                BEGIN
                    IF NEW."ESD_INC" <> OLD."ESD_INC"
                        AND NOT (
                            (OLD."ESD_INC" = 'detectada' AND NEW."ESD_INC" = 'en_revision')
                            OR (OLD."ESD_INC" = 'en_revision' AND NEW."ESD_INC" = 'resuelta')
                            OR (OLD."ESD_INC" = 'resuelta' AND NEW."ESD_INC" = 'cerrada')
                        )
                    THEN
                        RAISE EXCEPTION 'Transicion de estado no permitida: detectada > en_revision > resuelta > cerrada';
                    END IF;

                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            SQL);
            DB::unprepared('CREATE TRIGGER trg_incidencia_bu BEFORE UPDATE ON incidencia FOR EACH ROW EXECUTE FUNCTION validar_transicion_incidencia()');

            return;
        }

        DB::unprepared("
            CREATE TRIGGER trg_incidencia_bu BEFORE UPDATE ON incidencia
            FOR EACH ROW
            BEGIN
                IF NEW.ESD_INC <> OLD.ESD_INC THEN
                    IF NOT ( (OLD.ESD_INC = 'detectada'   AND NEW.ESD_INC = 'en_revision')
                          OR (OLD.ESD_INC = 'en_revision' AND NEW.ESD_INC = 'resuelta')
                          OR (OLD.ESD_INC = 'resuelta'    AND NEW.ESD_INC = 'cerrada') ) THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Transicion de estado no permitida: detectada > en_revision > resuelta > cerrada';
                    END IF;
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_incidencia_bu ON incidencia');
            DB::unprepared('DROP FUNCTION IF EXISTS validar_transicion_incidencia()');
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_incidencia_bu');
        Schema::dropIfExists('incidencia');
    }
};
