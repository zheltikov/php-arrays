<?php

namespace Zheltikov\Arrays;

use InvalidArgumentException;
use OutOfBoundsException;
use Zheltikov\Exceptions\InvalidOperationException;

/**
 * An ordered, iterable data structure.
 *
 * Class Vec
 * @package Zheltikov\Arrays
 */
final class Vec implements AnyArray
{
    /**
     * The internal array that is used to store the data.
     *
     * @var array
     */
    private array $array = [];

    /**
     * The current key of the iterator.
     *
     * @var int
     */
    private int $current_key = 0;

    /**
     * The length of this vec.
     *
     * @var int
     */
    private int $count = 0;

    /**
     * Vec constructor.
     * @param iterable|array $input The initial data.
     */
    private function __construct(iterable $input = [])
    {
        foreach ($input as $value) {
            $this[] = $value;
        }
    }

    /**
     * @return static A new instance.
     */
    public static function create(iterable $input = []): self
    {
        return new self($input);
    }

    /**
     * @return array A plain PHP array with the same values as this container.
     */
    public function toArray(): array
    {
        return $this->array;
    }

    /**
     * @param mixed $value The value to search.
     * @return bool The search result, `true` if the value was found.
     */
    public function contains($value): bool
    {
        foreach ($this as $v) {
            if ($value === $v) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param int $key The index to search.
     * @return bool The search result, `true` if the index was found.
     */
    public function containsKey($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Checks if two vecs are equal.
     * Elements are strictly compared.
     *
     * @param \Zheltikov\Arrays\Vec $a The first vec.
     * @param \Zheltikov\Arrays\Vec $b The second vec.
     * @return bool The comparison result, `true` if both vecs are equal.
     */
    public static function equal(self $a, self $b): bool
    {
        if ($a->count() !== $b->count()) {
            return false;
        }

        foreach ($a as $key => $value) {
            if ($value !== $b[$key]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Combines several vecs into a new one.
     *
     * @param \Zheltikov\Arrays\Vec $first The first vec.
     * @param \Zheltikov\Arrays\Vec ...$rest The other vecs.
     * @return static A new combined vecs.
     */
    public static function concat(self $first, self ...$rest): self
    {
        $result = self::create($first);

        foreach ($rest as $vec) {
            foreach ($vec as $value) {
                $result[] = $value;
            }
        }

        return $result;
    }

    /**
     * Creates a new Vec containing the keys of the input iterable.
     *
     * @param iterable $input The input iterable.
     * @return static A new vec containing the keys of the iterable.
     */
    public static function keys(iterable $input): self
    {
        $result = self::create();

        foreach ($input as $key => $_) {
            $result[] = $key;
        }

        return $result;
    }

    // -------------------------------------------------------------------------

    /**
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param int $offset The key to check.
     * @return bool The check result, `true` if the key exists.
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
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param int $offset The key to query.
     * @return mixed The value at the specified key.
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

        throw new OutOfBoundsException('Out of bounds vec access: invalid index ' . var_export($offset, true));
    }

    /**
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param int|null $offset The key at which to set the value.
     * @param mixed $value The value to set.
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
            throw new OutOfBoundsException('Out of bounds vec access: invalid index ' . var_export($offset, true));
        }

        $this->array[$offset] = $value;
    }

    /**
     * The supplied offset must be an `int`. Otherwise, an
     * `InvalidArgumentException` is thrown.
     *
     * Note: vecs only support deleting their last element.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param int $offset The key to remove.
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
     * @return mixed The current value.
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new OutOfBoundsException(
                'Out of bounds vec access: invalid index ' . var_export($this->current_key, true)
            );
        }

        return $this->array[$this->current_key];
    }

    /**
     * @return int The current key.
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
     * @return bool The check result, `true` if the current key is valid.
     */
    public function valid(): bool
    {
        return array_key_exists($this->current_key, $this->array);
    }

    // -------------------------------------------------------------------------

    /**
     * @return int The number of elements in this vec.
     */
    public function count(): int
    {
        return $this->count;
    }
}
