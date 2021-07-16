<?php

namespace Zheltikov\Arrays;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * Class Vec
 * @package Zheltikov\Collections
 */
final class Vec implements AnyArray
{
    /**
     * @var array
     */
    private array $array = [];

    /**
     * @var int
     */
    private int $current_key = 0;

    /**
     * @var int
     */
    private int $count = 0;

    /**
     * Vec constructor.
     * @param iterable|array $input
     */
    private function __construct(iterable $input = [])
    {
        foreach ($input as $value) {
            $this[] = $value;
        }
    }

    /**
     * @return static
     */
    public static function create(iterable $input = []): self
    {
        return new self($input);
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->array;
    }

    // -------------------------------------------------------------------------

    /**
     * Checks if a given offset exists in this `KeyedTraversable`.
     *
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * @param int $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid vec key: expected a key of type int, ' . gettype($offset) . ' given'
            );
        }

        return array_key_exists($offset, $this->array);
    }

    /**
     * Returns the value at the supplied offset in this `KeyedTraversable`.
     *
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * If there is no value at such offset, an `OutOfBoundsException` is thrown.
     *
     * @param int $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid vec key: expected a key of type int, ' . gettype($offset) . ' given'
            );
        }

        if (array_key_exists($offset, $this->array)) {
            return $this->array[$offset];
        }

        throw new OutOfBoundsException('Out of bounds vec access: invalid index ' . var_export($offset));
    }

    /**
     * Sets a value to a specific offset in this `Vec`.
     *
     * If the supplied offset is `null`, the value is appended, using the next
     * available integer key, instead of being stored in a key-value manner.
     *
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * @param int|null $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->array[] = $value;
            $this->count++;
            return;
        }

        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid vec key: expected a key of type int, ' . gettype($offset) . ' given'
            );
        }

        if (!array_key_exists($offset, $this->array)) {
            throw new OutOfBoundsException('Out of bounds vec access: invalid index ' . var_export($offset));
        }

        $this->array[$offset] = $value;
    }

    /**
     * Deletes a value by its offset from this `Vec`.
     *
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * Note: vecs only support deleting their last element.
     *
     * @param int $offset
     */
    public function offsetUnset($offset): void
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid vec key: expected a key of type int, ' . gettype($offset) . ' given'
            );
        }

        if (!array_key_exists($offset, $this->array)) {
            return;
        }

        $last_key = array_key_last($this->array);

        if ($last_key === null) {
            // This means that our array is empty
            return;
        }

        if ($offset !== $last_key) {
            throw new InvalidOperationException('Vecs do not support unsetting non-end elements');
        }

        unset($this->array[$offset]);
        $this->count--;
    }

    // -------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new OutOfBoundsException(
                'Out of bounds vec access: invalid index ' . var_export($this->current_key)
            );
        }

        return $this->array[$this->current_key];
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->current_key;
    }

    public function next(): void
    {
        $this->current_key++;
    }

    public function rewind(): void
    {
        $this->current_key = 0;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return array_key_exists($this->current_key, $this->array);
    }

    // -------------------------------------------------------------------------

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->count;
    }
}
