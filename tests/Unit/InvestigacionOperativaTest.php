<?php

namespace Tests\Unit;

use App\Support\InvestigacionOperativa\CadenaMarkov;
use App\Support\InvestigacionOperativa\HistorialEstados;
use App\Support\InvestigacionOperativa\TeoriaDecisiones;
use PHPUnit\Framework\TestCase;

/** Comprueba que el sistema reproduce los números del informe (Tablas 1, 2 y 3). */
class InvestigacionOperativaTest extends TestCase
{
    public function test_markov_reproduce_la_tabla_3_del_informe(): void
    {
        $m = new CadenaMarkov(HistorialEstados::MATRIZ_INFORME);

        $a3 = $m->distribucion([1, 0, 0], 3);
        $this->assertEqualsWithDelta(0.513, $a3[0], 0.001);
        $this->assertEqualsWithDelta(0.314, $a3[1], 0.001);
        $this->assertEqualsWithDelta(0.173, $a3[2], 0.001);

        $pi = $m->estacionaria();
        $this->assertEqualsWithDelta(0.468, $pi[0], 0.001);
        $this->assertEqualsWithDelta(0.340, $pi[1], 0.001);
        $this->assertEqualsWithDelta(0.191, $pi[2], 0.001);

        $mu = $m->diasHastaAgotamiento();
        $this->assertEqualsWithDelta(7.78, $mu[0], 0.01);
        $this->assertEqualsWithDelta(6.67, $mu[1], 0.01);
    }

    public function test_decisiones_tabla_1_valor_esperado_y_wald(): void
    {
        $r = (new TeoriaDecisiones(['A', 'B', 'C'], ['Alta', 'Media', 'Baja'],
            [[500, 300, 150], [200, 150, 100], [350, 250, 120]], [0.3, 0.5, 0.2]))->resolver();

        $this->assertEqualsWithDelta(330, $r['valores_esperados'][0], 0.001);
        $this->assertSame(0, $r['mejor_valor_esperado']);
        $this->assertSame(0, $r['mejor_wald']);
    }

    public function test_los_criterios_pueden_diferir(): void
    {
        $r = (new TeoriaDecisiones(['A', 'B'], ['Alta', 'Media', 'Baja'],
            [[500, 200, 20], [250, 220, 200]], [0.3, 0.5, 0.2]))->resolver();

        $this->assertSame(0, $r['mejor_valor_esperado']);
        $this->assertSame(1, $r['mejor_wald']);
        $this->assertFalse($r['coinciden']);
    }

    public function test_conteos_solo_cuentan_dias_consecutivos_y_toman_el_peor_estado_del_dia(): void
    {
        $c = HistorialEstados::conteos([
            [1, '2026-10-01', 0], [1, '2026-10-02', 1], [1, '2026-10-03', 2],
            [1, '2026-10-05', 0], [1, '2026-10-06', 0], [1, '2026-10-06', 1],
        ]);

        $this->assertSame([[0, 2, 0], [0, 0, 1], [0, 0, 0]], $c['porEstacion'][1]);
        $this->assertSame(1, $c['ultimo'][1]['estado']);
    }
}