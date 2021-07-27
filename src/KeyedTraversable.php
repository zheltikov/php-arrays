<?php

namespace Zheltikov\Arrays;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * Represents an object that can be iterated over using `foreach`, allowing a
 * key.
 *
 * Additionally, represents an object that can be indexed using square-bracket
 * syntax.
 *
 * Square bracket syntax is:
 *
 * ```php
 * $keyed_traversable[$key]
 * ```
 *
 * At this point, this includes objects with keys of type `int` and `string`.
 *
 * Interface KeyedTraversable
 * @package Zheltikov\Arrays
 */
interface KeyedTraversable extends
    ArrayAccess,
    Countable,
    Iterator
{
    /**
     * Checks if a given offset exists in this `KeyedTraversable`.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int $offset The key to check.
     * @return bool The check result, `true` if the key exists.
     */
    public function offsetExists($offset): bool;

    /**
     * Returns the value at the supplied offset in this `KeyedTraversable`.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * If there is no value at such offset, an `OutOfBoundsException` is thrown.
     *
     * @param string|int $offset The key to get.
     * @return mixed The value at the specified offset.
     */
    public function offsetGet($offset);

    /**
     * Sets a value to a specific offset in this `KeyedTraversable`.
     *
     * If the supplied offset is `null`, the value is appended, using the next
     * available integer key, instead of being stored in a key-value manner.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int|null $offset The offset at which to set the value.
     * @param mixed $value The value to set.
     */
    public function offsetSet($offset, $value): void;

    /**
     * Deletes a value by its offset from this `KeyedTraversable`.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int $offset The key to remove.
     */
    public function offsetUnset($offset): void;

    // -------------------------------------------------------------------------

    /**
     * Returns the element at the current position of this `KeyedTraversable`.
     *
     * If there is no valid element, an `OutOfBoundsException` is thrown.
     * This may happen if there are no elements at all, for example.
     *
     * @return mixed The current value.
     */
    public function current();

    /**
     * Returns the current internal offset of this `KeyedTraversable`.
     *
     * It may return `null` if there is no valid offset currently.
     *
     * @return string|int|null The current key.
     */
    public function key();

    /**
     * Advances the offset to the next element in this `KeyedTraversable`.
     */
    public function next(): void;

    /**
     * Resets the offset to the first element in this `KeyedTraversable`.
     */
    public function rewind(): void;

    /**
     * Checks if the current offset is valid in this `KeyedTraversable`.
     *
     * @return bool The check result, `true` if the current key is valid.
     */
    public function valid(): bool;

    // -------------------------------------------------------------------------

    /**
     * Returns the number of elements in this `KeyedTraversable`.
     *
     * @return int The length of this container.
     */
    public function count(): int;
}
