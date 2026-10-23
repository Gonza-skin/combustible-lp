<?php

namespace Tests\Feature;

use App\Models\Estacion;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::create('estacion', function (Blueprint $table): void {
            $table->increments('COD_EST');
            $table->string('NOM_EST');
            $table->string('DIR_EST')->nullable();
            $table->string('ZON_EST')->nullable();
            $table->decimal('LAT_EST', 10, 7)->nullable();
            $table->decimal('LON_EST', 10, 7)->nullable();
            $table->string('ESD_EST');
            $table->boolean('ACT_EST');
        });
    }

    public function test_the_application_returns_a_successful_response(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Explorar estaciones')
            ->assertSee('El mapa estará disponible pronto');
    }

    public function test_the_homepage_shows_active_stations_and_their_reported_status(): void
    {
        Estacion::create([
            'NOM_EST' => 'Estación de prueba',
            'DIR_EST' => 'Av. de prueba',
            'LAT_EST' => -16.5,
            'LON_EST' => -68.15,
            'ESD_EST' => 'nivel_bajo',
            'ACT_EST' => true,
        ]);
        Estacion::create([
            'NOM_EST' => 'Estación inactiva',
            'ESD_EST' => 'normal',
            'ACT_EST' => false,
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('Estación de prueba')
            ->assertSee('En escasez')
            ->assertDontSee('Estación inactiva');
    }
}
