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

    // -------------------------------------------------------------------------

    /**
     * Checks if a given offset exists in the current `AnyArray`.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int $offset
     * @return bool
     */
    public function offsetExists($offset): bool;

    /**
     * Returns the value at the supplied offset in the current `AnyArray`.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * If there is no value at such offset, an `OutOfBoundsException` is thrown.
     *
     * @param string|int $offset
     * @return mixed
     */
    public function offsetGet($offset);

    /**
     * Sets a value to a specific offset in the current `AnyArray`.
     *
     * If the supplied offset is `null`, the value is appended, using the next
     * available integer key, instead of being stored in a key-value manner.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int|null $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void;

    /**
     * Deletes a value by its offset from the current `AnyArray`.
     *
     * The supplied offset must be a valid array key, either a `string` or an
     * `int`. Otherwise, an `InvalidArgumentException` is thrown.
     *
     * @param string|int $offset
     */
    public function offsetUnset($offset): void;

    // -------------------------------------------------------------------------

    /**
     * Returns the element at the current position of the current `AnyArray`.
     *
     * If there is no valid element, an `OutOfBoundsException` is thrown.
     * This may happen if there are no elements at all, for example.
     *
     * @return mixed
     */
    public function current();

    /**
     * Returns the current internal offset of the current `AnyArray`.
     *
     * It may return `null` if there is no valid offset currently.
     *
     * @return string|int|null
     */
    public function key();

    /**
     * Advances the offset to the next element in the current `AnyArray`.
     */
    public function next(): void;

    /**
     * Resets the offset to the first element in the current `AnyArray`.
     */
    public function rewind(): void;

    /**
     * Checks if the current offset is valid in the current `AnyArray`.
     *
     * @return bool
     */
    public function valid(): bool;

    // -------------------------------------------------------------------------

    /**
     * Returns the number of elements in the current `AnyArray`.
     *
     * @return int
     */
    public function count(): int;
}
