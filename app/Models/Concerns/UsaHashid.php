<?php

namespace App\Models\Concerns;

use Hashids\Hashids;

trait UsaHashid
{
    protected static function hashids(): Hashids
    {
        // Hashids usa el prefijo del salt al mezclarlo; por eso va primero el nombre del modelo.
        return new Hashids(static::class.'|'.config('hashids.salt'), config('hashids.largo'));
    }

    public static function encodeRouteKey(int|string $id): string
    {
        return static::hashids()->encode((int) $id);
    }

    public static function decodeRouteKey(string $value): ?int
    {
        $ids = static::hashids()->decode($value);

        return count($ids) === 1 ? (int) $ids[0] : null;
    }

    public function getRouteKey(): string
    {
        return static::encodeRouteKey((int) $this->getKey());
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $id = static::decodeRouteKey((string) $value);

        abort_if($id === null, 404);

        return $this->where($this->getKeyName(), $id)->firstOrFail();
    }
}
