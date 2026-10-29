<?php

namespace App\Support\InvestigacionOperativa;

use InvalidArgumentException;
use RuntimeException;

/**
 * Cadena de Markov de 3 estados, observada una vez al día (informe, sección 7):
 *   0 = Disponible, 1 = Fila larga, 2 = Agotado.
 * Clase pura (sin Laravel ni base de datos) para poder probarla y explicarla fácil.
 */
final class CadenaMarkov
{
    public const ESTADOS = ['Disponible', 'Fila larga', 'Agotado'];

    private int $k;

    /** @param array<int, array<int, float>> $p Matriz de transición cuadrada; cada fila suma 1. */
    public function __construct(private array $p)
    {
        $this->k = count($p);
        foreach ($p as $i => $fila) {
            if (count($fila) !== $this->k) {
                throw new InvalidArgumentException('La matriz de transición debe ser cuadrada.');
            }
            if (abs(array_sum($fila) - 1) > 1e-6) {
                throw new InvalidArgumentException("La fila {$i} de la matriz no suma 1.");
            }
        }
    }

    /** p_ij = n_ij / n_i. Devuelve null si alguna fila no tiene observaciones. */
    public static function desdeConteos(array $n): ?array
    {
        $p = [];
        foreach ($n as $i => $fila) {
            $total = array_sum($fila);
            if ($total <= 0) {
                return null;
            }
            $p[$i] = array_map(fn ($x) => $x / $total, $fila);
        }

        return $p;
    }

    public function matriz(): array
    {
        return $this->p;
    }

    /** P^n */
    public function potencia(int $n): array
    {
        $r = self::identidad($this->k);
        for ($i = 0; $i < $n; $i++) {
            $r = self::multiplicar($r, $this->p);
        }

        return $r;
    }

    /** a_n = a_0 · P^n  (probabilidad de cada estado dentro de n días) */
    public function distribucion(array $a0, int $n): array
    {
        $pn = $this->potencia($n);
        $res = array_fill(0, $this->k, 0.0);
        for ($j = 0; $j < $this->k; $j++) {
            for ($i = 0; $i < $this->k; $i++) {
                $res[$j] += $a0[$i] * $pn[$i][$j];
            }
        }

        return $res;
    }

    /** Largo plazo: π·P = π con Σπ = 1. */
    public function estacionaria(): array
    {
        $k = $this->k;
        $a = [];
        for ($i = 0; $i < $k; $i++) {
            for ($j = 0; $j < $k; $j++) {
                $a[$i][$j] = $this->p[$j][$i] - ($i === $j ? 1 : 0); // (Pᵀ − I)
            }
        }
        $a[$k - 1] = array_fill(0, $k, 1.0);          // última ecuación: Σπ = 1
        $b = array_fill(0, $k, 0.0);
        $b[$k - 1] = 1.0;

        return self::resolver($a, $b);
    }

    /**
     * Días esperados hasta el PRIMER agotamiento: μ = (I − N)⁻¹ · 1,
     * donde N es P sin la fila/columna del estado Agotado. Devuelve [μ_Disponible, μ_FilaLarga].
     */
    public function diasHastaAgotamiento(): array
    {
        $m = $this->k - 1;
        $a = [];
        for ($i = 0; $i < $m; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $a[$i][$j] = ($i === $j ? 1 : 0) - $this->p[$i][$j];
            }
        }

        return self::resolver($a, array_fill(0, $m, 1.0));
    }

    public static function identidad(int $k): array
    {
        $r = [];
        for ($i = 0; $i < $k; $i++) {
            for ($j = 0; $j < $k; $j++) {
                $r[$i][$j] = $i === $j ? 1.0 : 0.0;
            }
        }

        return $r;
    }

    public static function multiplicar(array $a, array $b): array
    {
        $n = count($a);
        $m = count($b[0]);
        $r = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $m; $j++) {
                $s = 0.0;
                for ($t = 0; $t < count($b); $t++) {
                    $s += $a[$i][$t] * $b[$t][$j];
                }
                $r[$i][$j] = $s;
            }
        }

        return $r;
    }

    /** Resuelve A·x = b por eliminación de Gauss con pivoteo parcial. */
    private static function resolver(array $a, array $b): array
    {
        $n = count($b);
        for ($c = 0; $c < $n; $c++) {
            $max = $c;
            for ($r = $c + 1; $r < $n; $r++) {
                if (abs($a[$r][$c]) > abs($a[$max][$c])) {
                    $max = $r;
                }
            }
            if (abs($a[$max][$c]) < 1e-12) {
                throw new RuntimeException('El sistema no tiene solución única (la cadena no es regular).');
            }
            [$a[$c], $a[$max]] = [$a[$max], $a[$c]];
            [$b[$c], $b[$max]] = [$b[$max], $b[$c]];
            for ($r = $c + 1; $r < $n; $r++) {
                $f = $a[$r][$c] / $a[$c][$c];
                for ($j = $c; $j < $n; $j++) {
                    $a[$r][$j] -= $f * $a[$c][$j];
                }
                $b[$r] -= $f * $b[$c];
            }
        }
        $x = array_fill(0, $n, 0.0);
        for ($i = $n - 1; $i >= 0; $i--) {
            $s = $b[$i];
            for ($j = $i + 1; $j < $n; $j++) {
                $s -= $a[$i][$j] * $x[$j];
            }
            $x[$i] = $s / $a[$i][$i];
        }

        return $x;
    }
}