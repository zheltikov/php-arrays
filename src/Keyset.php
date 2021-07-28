<?php

namespace Zheltikov\Arrays;

use InvalidArgumentException;
use OutOfBoundsException;
use Zheltikov\Exceptions\InvalidOperationException;

/**
 * An ordered data structure without duplicates. It can only contain `string` or
 * `int` values.
 *
 * Class Keyset
 * @package Zheltikov\Arrays
 */
final class Keyset implements AnyArray
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
     * @var string|int|null
     */
    private $current_key = null;

    /**
     * The number of elements in this keyset.
     *
     * @var int|null
     */
    private ?int $count = null;

    /**
     * Keyset constructor.
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
     * For Keysets, this method is interchangeable with `containsKey`.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param mixed $value The value to search.
     * @return bool The search result, `true` if the value was found.
     */
    public function contains($value): bool
    {
        return $this->containsKey($value);
    }

    /**
     * For Keysets, this method is interchangeable with `contains`.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param string|int $key The key to search.
     * @return bool The search result, `true` if the key was found.
     */
    public function containsKey($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Checks if two keysets contain the same values.
     *
     * @param \Zheltikov\Arrays\Keyset $a The first keyset.
     * @param \Zheltikov\Arrays\Keyset $b The second keyset.
     * @return bool The comparison result, `true` if both keysets are equal.
     */
    public static function equal(self $a, self $b): bool
    {
        if ($a->count() !== $b->count()) {
            return false;
        }

        foreach ($a as $value) {
            if (!$b->contains($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if two keysets contain the same values, and in the same order.
     * Elements are strictly compared.
     *
     * @param \Zheltikov\Arrays\Keyset $a The first keyset.
     * @param \Zheltikov\Arrays\Keyset $b The second keyset.
     * @return bool The comparison result, `true` if both keysets are identical.
     */
    public static function match(self $a, self $b): bool
    {
        if ($a->count() !== $b->count()) {
            return false;
        }

        $a->rewind();
        $b->rewind();

        while (true) {
            if (!$a->valid() || !$b->valid()) {
                break;
            }

            if ($a->current() !== $b->current()) {
                return false;
            }

            $a->next();
            $b->next();
        }

        return true;
    }

    /**
     * Combines several keysets into a new one.
     *
     * @param \Zheltikov\Arrays\Keyset $first The first keyset.
     * @param \Zheltikov\Arrays\Keyset ...$rest The other keysets.
     * @return static A new combined keyset.
     */
    public static function union(self $first, self ...$rest): self
    {
        $result = self::create($first);

        foreach ($rest as $keyset) {
            foreach ($keyset as $value) {
                $result[] = $value;
            }
        }

        return $result;
    }

    // -------------------------------------------------------------------------

    /**
     * @param string|int $offset The key to check.
     * @return bool The check result, `true` if the key exists.
     */
    public function offsetExists($offset): bool
    {
        if (!is_string($offset) && !is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid keyset key: expected a key of type int or string, ' . gettype($offset) . ' given'
            );
        }

        return array_key_exists($offset, $this->array);
    }

    /**
     * @param string|int $offset The key to query.
     * @return mixed The value at the specified key.
     */
    public function offsetGet($offset)
    {
        if (!is_string($offset) && !is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid keyset key: expected a key of type int or string, ' . gettype($offset) . ' given'
            );
        }

        if (array_key_exists($offset, $this->array)) {
            return $this->array[$offset];
        }

        throw new OutOfBoundsException('Out of bounds keyset access: invalid index ' . var_export($offset, true));
    }

    /**
     * Adds a value to this `Keyset`.
     *
     * If the supplied offset is `null`, an `InvalidOperationException` is
     * thrown, this is because keysets do not support appending values.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param null $offset The key at which to set the value.
     * @param string|int $value The value to set.
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset !== null) {
            throw new InvalidOperationException('Invalid operation on keyset');
        }

        if (!is_string($value) && !is_int($value)) {
            throw new InvalidArgumentException(
                'Invalid keyset key: expected a key of type int or string, ' . gettype($value) . ' given'
            );
        }

        $this->array[$value] = $value;
        $this->count = null;
    }

    /**
     * @param string|int $offset The key to remove.
     */
    public function offsetUnset($offset): void
    {
        if (!is_string($offset) && !is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid keyset key: expected a key of type int or string, ' . gettype($offset) . ' given'
            );
        }

        unset($this->array[$offset]);
        $this->count = null;
    }

    // -------------------------------------------------------------------------

    /**
     * @return mixed The current value.
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new OutOfBoundsException(
                'Out of bounds keyset access: invalid index ' . var_export($this->current_key, true)
            );
        }

        return $this->array[$this->current_key];
    }

    /**
     * @return string|int|null The current key.
     */
    public function key()
    {
        return $this->current_key;
    }

    public function next(): void
    {
        $take_key = false;

        foreach ($this->array as $key => $_) {
            if ($take_key) {
                $this->current_key = $key;
                return;
            }

            if ($this->current_key === $key) {
                $take_key = true;
            }
        }

        $this->current_key = null;
    }

    public function rewind(): void
    {
        foreach ($this->array as $key => $_) {
            $this->current_key = $key;
            return;
        }

        $this->current_key = null;
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
     * @return int The number of keys in this keyset.
     */
    public function count(): int
    {
        if ($this->count === null) {
            $this->count = count($this->array);
        }

        return $this->count;
    }
}
