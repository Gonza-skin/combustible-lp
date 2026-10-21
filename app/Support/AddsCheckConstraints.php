<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use RuntimeException;

trait AddsCheckConstraints
{
    protected function addCheckConstraints(string $table, array $constraints): void
    {
        $connection = DB::connection();
        $grammar = $connection->getQueryGrammar();
        $wrappedTable = $grammar->wrapTable($table);

        foreach ($constraints as $name => $expression) {
            if ($connection->getDriverName() === 'pgsql') { $expression = preg_replace('/\b([A-Z]{3}_[A-Z]{3})\b/', '"$1"', $expression); } // pgsql-comillas
            if (! preg_match('/^[a-z][a-z0-9_]*$/', $name)) {
                throw new RuntimeException("Invalid check constraint name: {$name}");
            }

            if ($connection->getDriverName() === 'sqlite') {
                continue;
            }

            if (! in_array($connection->getDriverName(), ['mysql', 'pgsql'], true)) {
                throw new RuntimeException(
                    "Check constraints are not implemented for the {$connection->getDriverName()} database driver."
                );
            }

            $connection->statement(
                "ALTER TABLE {$wrappedTable} ADD CONSTRAINT {$grammar->wrap($name)} CHECK ({$expression})"
            );
        }
    }
}
