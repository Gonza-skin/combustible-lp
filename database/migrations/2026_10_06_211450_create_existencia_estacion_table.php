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
        Schema::create('existencia_estacion', function (Blueprint $table) {
            $table->unsignedInteger('COD_EST');
            $table->unsignedInteger('COD_COM');
            $table->decimal('CAN_EXI', 10, 2)->default(0);
            $table->timestamp('FEC_EXI')->useCurrent()->useCurrentOnUpdate();

            $table->primary(['COD_EST', 'COD_COM']);
            $table->foreign('COD_EST')->references('COD_EST')->on('estacion');
            $table->foreign('COD_COM')->references('COD_COM')->on('tipo_combustible');
        });

        $this->addCheckConstraints('existencia_estacion', [
            'chk_exi_can' => 'CAN_EXI >= 0',
        ]);

        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared(<<<'SQL'
                CREATE FUNCTION sync_estacion_estado_existencia() RETURNS TRIGGER AS $$
                BEGIN
                    UPDATE "estacion"
                    SET "ESD_EST" = CASE
                        WHEN NEW."CAN_EXI" = 0 THEN 'sin_combustible'
                        WHEN NEW."CAN_EXI" <= "MIN_EST" THEN 'nivel_bajo'
                        ELSE 'normal'
                    END
                    WHERE "COD_EST" = NEW."COD_EST";
                    RETURN NEW;
                END;
                $$ LANGUAGE plpgsql
            SQL);
            DB::unprepared('CREATE TRIGGER trg_existencia_ai AFTER INSERT OR UPDATE ON existencia_estacion FOR EACH ROW EXECUTE FUNCTION sync_estacion_estado_existencia()');

            return;
        }

        DB::unprepared("
            CREATE TRIGGER trg_existencia_ai AFTER INSERT ON existencia_estacion
            FOR EACH ROW
            BEGIN
                UPDATE estacion SET ESD_EST = CASE
                    WHEN NEW.CAN_EXI = 0 THEN 'sin_combustible'
                    WHEN NEW.CAN_EXI <= MIN_EST THEN 'nivel_bajo'
                    ELSE 'normal' END
                WHERE COD_EST = NEW.COD_EST;
            END;
        ");

        DB::unprepared("
            CREATE TRIGGER trg_existencia_au AFTER UPDATE ON existencia_estacion
            FOR EACH ROW
            BEGIN
                UPDATE estacion SET ESD_EST = CASE
                    WHEN NEW.CAN_EXI = 0 THEN 'sin_combustible'
                    WHEN NEW.CAN_EXI <= MIN_EST THEN 'nivel_bajo'
                    ELSE 'normal' END
                WHERE COD_EST = NEW.COD_EST;
            END;
        ");
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'pgsql') {
            DB::unprepared('DROP TRIGGER IF EXISTS trg_existencia_ai ON existencia_estacion');
            DB::unprepared('DROP FUNCTION IF EXISTS sync_estacion_estado_existencia()');
        }

        DB::unprepared('DROP TRIGGER IF EXISTS trg_existencia_au');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_existencia_ai');
        Schema::dropIfExists('existencia_estacion');
    }
};
