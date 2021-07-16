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
}
