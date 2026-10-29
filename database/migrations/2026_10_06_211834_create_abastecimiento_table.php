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
        Schema::create('abastecimiento', function (Blueprint $table) {
            $table->increments('COD_ABA');
            $table->unsignedInteger('COD_SOL')->unique();
            $table->unsignedInteger('COD_COM');
            $table->unsignedInteger('COD_CIS')->nullable();
            $table->decimal('CAN_ABA', 10, 2);
            $table->string('ESD_ABA', 20)->default('pendiente');
            $table->dateTime('HSA_ABA')->nullable();
            $table->dateTime('HEL_ABA')->nullable();
            $table->dateTime('HRL_ABA')->nullable();
            $table->boolean('RET_ABA')->nullable();
            $table->integer('MRE_ABA')->nullable();
            $table->decimal('CCA_ABA', 10, 2)->nullable();
            $table->decimal('CRE_ABA', 10, 2)->nullable();
            $diferenciaCalculada = DB::connection()->getDriverName() === 'pgsql'
                ? '"CCA_ABA" - "CRE_ABA"'
                : 'CASE WHEN CCA_ABA IS NULL OR CRE_ABA IS NULL THEN NULL ELSE CCA_ABA - CRE_ABA END';
            $table->decimal('DIF_ABA', 10, 2)->storedAs($diferenciaCalculada);
            $table->unsignedInteger('COD_USU')->nullable();
            $table->timestamp('FEC_ABA')->useCurrent();

            $table->foreign('COD_SOL')->references('COD_SOL')->on('solicitud_abastecimiento');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_CIS')->references('COD_CIS')->on('cisterna');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
        });

        $this->addCheckConstraints('abastecimiento', [
            'chk_aba_can' => 'CAN_ABA > 0',
            'chk_aba_esd' => "ESD_ABA IN ('pendiente','asignado','en_transito','entregado','cerrado','cancelado')",
        ]);

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared(<<<'SQL'
                CREATE FUNCTION validate_abastecimiento() RETURNS TRIGGER AS $$
                DECLARE
                    v_cap DECIMAL(10,2);
                    v_com INTEGER;
                    v_tpt INTEGER;
                BEGIN
                    IF NEW."COD_CIS" IS NOT NULL THEN
                        SELECT "CAP_CIS", "COD_COM"
                        INTO v_cap, v_com
                        FROM "cisterna"
                        WHERE "COD_CIS" = NEW."COD_CIS";

                        IF v_cap < NEW."CAN_ABA" THEN
                            RAISE EXCEPTION 'La capacidad de la cisterna es menor a la cantidad solicitada';
                        END IF;
                        IF v_com <> NEW."COD_COM" THEN
                            RAISE EXCEPTION 'La cisterna transporta un tipo de combustible distinto al requerido';
                        END IF;
                    END IF;

                    IF NEW."HSA_ABA" IS NOT NULL AND NEW."HEL_ABA" IS NULL THEN
                        SELECT e."TPT_EST"
                        INTO v_tpt
                        FROM "solicitud_abastecimiento" s
                        JOIN "estacion" e ON e."COD_EST" = s."COD_EST"
                        WHERE s."COD_SOL" = NEW."COD_SOL";
                        NEW."HEL_ABA" := NEW."HSA_ABA" + make_interval(mins => v_tpt);
                    END IF;

                    IF NEW."HRL_ABA" IS NOT NULL AND NEW."HEL_ABA" IS NOT NULL THEN
                        NEW."MRE_ABA" := GREATEST(
                            FLOOR(EXTRACT(EPOCH FROM (NEW."HRL_ABA" - NEW."HEL_ABA")) / 60)::INTEGER,
                            0
                        );
                        NEW."RET_ABA" := NEW."MRE_ABA" > 30;
                    END IF;

                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            SQL);
            DB::unprepared('CREATE TRIGGER trg_abastecimiento_biu BEFORE INSERT OR UPDATE ON abastecimiento FOR EACH ROW EXECUTE FUNCTION validate_abastecimiento()');

            return;
        }

        DB::unprepared("
            CREATE TRIGGER trg_abastecimiento_bi BEFORE INSERT ON abastecimiento
            FOR EACH ROW
            BEGIN
                DECLARE v_cap DECIMAL(10,2);
                DECLARE v_com INT;
                DECLARE v_tpt INT;
                IF NEW.COD_CIS IS NOT NULL THEN
                    SELECT CAP_CIS, COD_COM INTO v_cap, v_com FROM cisterna WHERE COD_CIS = NEW.COD_CIS;
                    IF v_cap < NEW.CAN_ABA THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La capacidad de la cisterna es menor a la cantidad solicitada';
                    END IF;
                    IF v_com <> NEW.COD_COM THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cisterna transporta un tipo de combustible distinto al requerido';
                    END IF;
                END IF;
                IF NEW.HSA_ABA IS NOT NULL AND NEW.HEL_ABA IS NULL THEN
                    SELECT e.TPT_EST INTO v_tpt FROM solicitud_abastecimiento s
                    JOIN estacion e ON e.COD_EST = s.COD_EST WHERE s.COD_SOL = NEW.COD_SOL;
                    SET NEW.HEL_ABA = DATE_ADD(NEW.HSA_ABA, INTERVAL v_tpt MINUTE);
                END IF;
                IF NEW.HRL_ABA IS NOT NULL AND NEW.HEL_ABA IS NOT NULL THEN
                    SET NEW.MRE_ABA = GREATEST(TIMESTAMPDIFF(MINUTE, NEW.HEL_ABA, NEW.HRL_ABA), 0);
                    SET NEW.RET_ABA = (NEW.MRE_ABA > 30);
                END IF;
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER trg_abastecimiento_bu BEFORE UPDATE ON abastecimiento
            FOR EACH ROW
            BEGIN
                DECLARE v_cap DECIMAL(10,2);
                DECLARE v_com INT;
                DECLARE v_tpt INT;
                IF NEW.COD_CIS IS NOT NULL THEN
                    SELECT CAP_CIS, COD_COM INTO v_cap, v_com FROM cisterna WHERE COD_CIS = NEW.COD_CIS;
                    IF v_cap < NEW.CAN_ABA THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La capacidad de la cisterna es menor a la cantidad solicitada';
                    END IF;
                    IF v_com <> NEW.COD_COM THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La cisterna transporta un tipo de combustible distinto al requerido';
                    END IF;
                END IF;
                IF NEW.HSA_ABA IS NOT NULL AND NEW.HEL_ABA IS NULL THEN
                    SELECT e.TPT_EST INTO v_tpt FROM solicitud_abastecimiento s
                    JOIN estacion e ON e.COD_EST = s.COD_EST WHERE s.COD_SOL = NEW.COD_SOL;
                    SET NEW.HEL_ABA = DATE_ADD(NEW.HSA_ABA, INTERVAL v_tpt MINUTE);
                END IF;
                IF NEW.HRL_ABA IS NOT NULL AND NEW.HEL_ABA IS NOT NULL THEN
                    SET NEW.MRE_ABA = GREATEST(TIMESTAMPDIFF(MINUTE, NEW.HEL_ABA, NEW.HRL_ABA), 0);
                    SET NEW.RET_ABA = (NEW.MRE_ABA > 30);
                END IF;
            END;
        ");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_abastecimiento_biu ON abastecimiento');
            DB::unprepared('DROP FUNCTION IF EXISTS validate_abastecimiento()');
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_abastecimiento_bu');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_abastecimiento_bi');
        Schema::dropIfExists('abastecimiento');
    }
};
