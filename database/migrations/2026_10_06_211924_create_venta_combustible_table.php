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
        Schema::create('venta_combustible', function (Blueprint $table) {
            $table->bigIncrements('COD_VEN');
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_VEH');
            $table->unsignedInteger('COD_COM');
            $table->decimal('CAN_VEN', 10, 2);
            $table->decimal('PRE_VEN', 10, 2);
            $totalCalculado = DB::connection()->getDriverName() === 'pgsql'
                ? '"CAN_VEN" * "PRE_VEN"'
                : 'CAN_VEN * PRE_VEN';
            $table->decimal('VAL_VEN', 10, 2)->storedAs($totalCalculado);
            $table->unsignedInteger('COD_USU');
            $table->timestamp('FEC_VEN')->useCurrent();

            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_VEH')->references('COD_VEH')->on('automovil');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
            $table->foreign('COD_USU')->references('COD_USU')->on('usuario');
            $table->index(['COD_VEH', 'FEC_VEN'], 'idx_ven_veh_fec');
        });

        $this->addCheckConstraints('venta_combustible', [
            'chk_ven_can' => 'CAN_VEN > 0',
            'chk_ven_pre' => 'PRE_VEN > 0',
        ]);

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared(<<<'SQL'
                CREATE FUNCTION registrar_venta_combustible() RETURNS TRIGGER AS $$
                DECLARE
                    v_stock DECIMAL(10,2);
                BEGIN
                    SELECT "CAN_EXI"
                    INTO v_stock
                    FROM "existencia_estacion"
                    WHERE "COD_EST" = NEW."COD_EST" AND "COD_COM" = NEW."COD_COM"
                    FOR UPDATE;

                    IF NOT FOUND OR v_stock < NEW."CAN_VEN" THEN
                        RAISE EXCEPTION 'No hay existencias suficientes para registrar esta venta';
                    END IF;

                    UPDATE "existencia_estacion"
                    SET "CAN_EXI" = "CAN_EXI" - NEW."CAN_VEN"
                    WHERE "COD_EST" = NEW."COD_EST" AND "COD_COM" = NEW."COD_COM";

                    INSERT INTO "movimiento_existencia"
                        ("COD_EST", "COD_COM", "TIP_MOV", "CAN_MOV", "COD_USU", "REF_MOV")
                    VALUES (
                        NEW."COD_EST",
                        NEW."COD_COM",
                        'salida',
                        NEW."CAN_VEN",
                        NEW."COD_USU",
                        CONCAT('venta_combustible #', NEW."COD_VEN")
                    );

                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            SQL);
            DB::unprepared('CREATE TRIGGER trg_venta_ai AFTER INSERT ON venta_combustible FOR EACH ROW EXECUTE FUNCTION registrar_venta_combustible()');

            return;
        }

        DB::unprepared("
            CREATE TRIGGER trg_venta_ai AFTER INSERT ON venta_combustible
            FOR EACH ROW
            BEGIN
                DECLARE v_stock DECIMAL(10,2);
                SELECT CAN_EXI INTO v_stock FROM existencia_estacion
                  WHERE COD_EST = NEW.COD_EST AND COD_COM = NEW.COD_COM
                  FOR UPDATE;
                IF v_stock IS NULL OR v_stock < NEW.CAN_VEN THEN
                    SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'No hay existencias suficientes para registrar esta venta';
                END IF;
                UPDATE existencia_estacion
                   SET CAN_EXI = CAN_EXI - NEW.CAN_VEN
                 WHERE COD_EST = NEW.COD_EST AND COD_COM = NEW.COD_COM;
                INSERT INTO movimiento_existencia (COD_EST, COD_COM, TIP_MOV, CAN_MOV, COD_USU, REF_MOV)
                VALUES (NEW.COD_EST, NEW.COD_COM, 'salida', NEW.CAN_VEN, NEW.COD_USU, CONCAT('venta_combustible #', NEW.COD_VEN));
            END;
        ");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_venta_ai ON venta_combustible');
            DB::unprepared('DROP FUNCTION IF EXISTS registrar_venta_combustible()');
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_venta_ai');
        Schema::dropIfExists('venta_combustible');
    }
};
