<?php

namespace Tests\Feature;

use App\Models\Estacion;
use App\Models\AutomovilRegistro;
use App\Models\Abastecimiento;
use App\Models\Cisterna;
use App\Models\Conductor;
use App\Models\IncidenciaRegistro;
use App\Models\MantenimientoCisterna;
use App\Models\PlantaDeposito;
use App\Models\SolicitudAbastecimiento;
use App\Models\TanqueAlmacenamiento;
use App\Models\TipoCombustible;
use App\Models\Usuario;
use App\Models\VentaCombustibleRegistro;
use App\Http\Middleware\CheckRole;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class HashidRoutingTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware([Authenticate::class, CheckRole::class]);

        Schema::create('estacion', function (Blueprint $table): void {
            $table->increments('COD_EST');
            $table->string('NOM_EST', 120);
            $table->string('DIR_EST', 200)->nullable();
            $table->string('ZON_EST', 80)->nullable();
            $table->decimal('LAT_EST', 10, 7)->nullable();
            $table->decimal('LON_EST', 10, 7)->nullable();
            $table->decimal('MIN_EST')->nullable();
            $table->decimal('MAX_EST')->nullable();
            $table->unsignedInteger('TPT_EST')->nullable();
            $table->string('ESD_EST')->nullable();
            $table->boolean('ACT_EST')->nullable();
        });
    }

    public function test_it_generates_and_resolves_hashed_route_keys(): void
    {
        $estacion = Estacion::create(['NOM_EST' => 'Estación de prueba']);
        $hash = $estacion->getRouteKey();

        $this->assertNotSame((string) $estacion->getKey(), $hash);
        $this->assertSame($estacion->getKey(), Estacion::decodeRouteKey($hash));
        $this->assertStringContainsString('/estaciones/'.$hash.'/edit', route('estaciones.edit', $estacion));
        $this->get(route('estaciones.edit', $estacion))
            ->assertOk()
            ->assertSee($hash);
    }

    public function test_it_rejects_plain_numeric_and_invalid_route_keys(): void
    {
        Estacion::create(['NOM_EST' => 'Estación de prueba']);

        $this->get('/estaciones/1/edit')->assertNotFound();
        $this->assertNull(Estacion::decodeRouteKey('invalid'));
    }

    public function test_all_models_use_distinct_hashes_for_their_route_keys(): void
    {
        $models = [
            Estacion::class,
            TipoCombustible::class,
            Cisterna::class,
            Conductor::class,
            MantenimientoCisterna::class,
            Usuario::class,
            AutomovilRegistro::class,
            VentaCombustibleRegistro::class,
            IncidenciaRegistro::class,
            Abastecimiento::class,
            SolicitudAbastecimiento::class,
            PlantaDeposito::class,
            TanqueAlmacenamiento::class,
        ];

        $hashes = [];

        foreach ($models as $model) {
            $hash = $model::encodeRouteKey(42);

            $this->assertSame(42, $model::decodeRouteKey($hash));
            $hashes[] = $hash;
        }

        $this->assertCount(count($models), array_unique($hashes));
    }
}
