<?php

namespace Zheltikov\Arrays;

/**
 * Interface AnyArray
 * @package Zheltikov\Arrays
 */
interface AnyArray extends KeyedTraversable
{
    /**
     * Creates a new instance of the current `AnyArray` type from the supplied
     * iterable object, empty by default.
     *
     * @param iterable|array $input
     * @return static
     */
    public static function create(iterable $input = []): self;

    /**
     * Converts the current `AnyArray` to a plain PHP array.
     *
     * @return array
     */
    public function toArray(): array;

    /**
     * Returns true if the current `AnyArray` contains the value.
     * Strict equality is used.
     *
     * @param mixed $value
     * @return bool
     */
    public function contains($value): bool;

    /**
     * Returns true if the current `AnyArray` contains the key.
     *
     * The given key must be a valid array key, either a `string` or an `int`.
     * Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int $key
     * @return bool
     */
    public function containsKey($key): bool;
}
