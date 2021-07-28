<?php

namespace Zheltikov\Arrays;

/**
 * The common interface for all array types (container objects).
 * This currently includes `vec`, `dict` and `keyset`.
 *
 * Interface AnyArray
 * @package Zheltikov\Arrays
 */
interface AnyArray extends KeyedTraversable
{
    /**
     * Creates a new instance of the current `AnyArray` type from the supplied
     * iterable object, empty by default.
     *
     * @param iterable|array $input An iterable to use when initializing the
     *        current `AnyArray`.
     * @return static A new `AnyArray` instance.
     */
    public static function create(iterable $input = []): self;

    /**
     * Converts the current `AnyArray` to a plain PHP array.
     *
     * @return array A plain PHP array with the same values as this `AnyArray`.
     */
    public function toArray(): array;

    /**
     * Returns true if the current `AnyArray` contains the value.
     * Strict equality is used.
     *
     * @param mixed $value The value to search.
     * @return bool The search result, `true` if the value was found.
     */
    public function contains($value): bool;

    /**
     * Returns true if the current `AnyArray` contains the key.
     *
     * The given key must be a valid array key, either a `string` or an `int`.
     * Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int $key The key to search.
     * @return bool The search result, `true` if the key was found.
     */
    public function containsKey($key): bool;
}
