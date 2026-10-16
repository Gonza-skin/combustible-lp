<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
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
            $table->decimal('DIF_ABA', 10, 2)->storedAs("CASE WHEN CCA_ABA IS NULL OR CRE_ABA IS NULL THEN NULL ELSE CCA_ABA - CRE_ABA END");
            $table->unsignedInteger('COD_USU')->nullable();
            $table->timestamp('FEC_ABA')->useCurrent();

            $table->foreign('COD_SOL')->references('COD_SOL')->on('solicitud_abastecimiento');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_CIS')->references('COD_CIS')->on('cisterna');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->check('CAN_ABA > 0', 'chk_aba_can');
            $table->check("ESD_ABA IN ('pendiente','asignado','en_transito','entregado','cerrado','cancelado')", 'chk_aba_esd');
        });

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
        DB::unprepared('DROP TRIGGER IF EXISTS trg_abastecimiento_bu');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_abastecimiento_bi');
        Schema::dropIfExists('abastecimiento');
    }
};
