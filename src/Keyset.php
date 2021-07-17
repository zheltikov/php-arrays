<?php

namespace Zheltikov\Arrays;

use InvalidArgumentException;
use OutOfBoundsException;
use Zheltikov\Exceptions\InvalidOperationException;

/**
 * Class Keyset
 * @package Zheltikov\Collections
 */
final class Keyset implements AnyArray
{
    /**
     * @var array
     */
    private array $array = [];

    /**
     * @var string|int|null
     */
    private $current_key = null;

    /**
     * @var int|null
     */
    private ?int $count = null;

    /**
     * Keyset constructor.
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

    /**
     * For Keysets, this method is interchangeable with `containsKey`.
     *
     * ---
     *
     * {@inheritDoc}
     *
     * @param mixed $value
     * @return bool
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
     * @param string|int $key
     * @return bool
     */
    public function containsKey($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Checks if two keysets contain the same values.
     *
     * @param \Zheltikov\Arrays\Keyset $a
     * @param \Zheltikov\Arrays\Keyset $b
     * @return bool
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
     * @param \Zheltikov\Arrays\Keyset $a
     * @param \Zheltikov\Arrays\Keyset $b
     * @return bool
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
     * @param \Zheltikov\Arrays\Keyset $first
     * @param \Zheltikov\Arrays\Keyset ...$rest
     * @return static
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
     * @param string|int $offset
     * @return bool
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
     * @param string|int $offset
     * @return mixed
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

        throw new OutOfBoundsException('Out of bounds keyset access: invalid index ' . var_export($offset));
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
     * @param null $offset
     * @param string|int $value
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
     * @param string|int $offset
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
     * @return string|int
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new OutOfBoundsException(
                'Out of bounds keyset access: invalid index ' . var_export($this->current_key)
            );
        }

        return $this->array[$this->current_key];
    }

    /**
     * @return string|int|null
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
        if ($this->count === null) {
            $this->count = count($this->array);
        }

        return $this->count;
    }
}
