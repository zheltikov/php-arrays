<?php

namespace Zheltikov\Arrays;

use InvalidArgumentException;
use OutOfBoundsException;

/**
 * Class Dict
 * @package Zheltikov\Arrays
 */
final class Dict implements AnyArray
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
     * Dict constructor.
     * @param iterable|array $input
     */
    private function __construct(iterable $input = [])
    {
        foreach ($input as $key => $value) {
            $this[$key] = $value;
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
     * @param mixed $value
     * @return bool
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
     * @param string|int $key
     * @return bool
     */
    public function containsKey($key): bool
    {
        return $this->offsetExists($key);
    }

    /**
     * Checks if two dicts have the same key/value pairs.
     * Elements are checked using strict comparison.
     *
     * @param \Zheltikov\Arrays\Dict $a
     * @param \Zheltikov\Arrays\Dict $b
     * @return bool
     */
    public static function equal(self $a, self $b): bool
    {
        if ($a->count() !== $b->count()) {
            return false;
        }

        foreach ($a as $key => $value) {
            if (
                !$b->containsKey($key)
                || $b[$key] !== $value
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * Checks if two dicts have the same key/value pairs, and in the same order.
     * Elements are strictly compared.
     *
     * @param \Zheltikov\Arrays\Dict $a
     * @param \Zheltikov\Arrays\Dict $b
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

            if (
                $a->key() !== $b->key()
                || $a->current() !== $b->current()
            ) {
                return false;
            }

            $a->next();
            $b->next();
        }

        return true;
    }

    /**
     * Combines several dicts into a new one.
     * Last key/value combination wins.
     *
     * @param \Zheltikov\Arrays\Dict $first
     * @param \Zheltikov\Arrays\Dict ...$rest
     * @return static
     */
    public static function merge(self $first, self ...$rest): self
    {
        $result = self::create($first);

        foreach ($rest as $dict) {
            foreach ($dict as $key => $value) {
                $result[$key] = $value;
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
                'Invalid dict key: expected a key of type int or string, ' . gettype($offset) . ' given'
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
                'Invalid dict key: expected a key of type int or string, ' . gettype($offset) . ' given'
            );
        }

        if (array_key_exists($offset, $this->array)) {
            return $this->array[$offset];
        }

        throw new OutOfBoundsException('Out of bounds dict access: invalid index ' . var_export($offset, true));
    }

    /**
     * @param string|int|null $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        if ($offset === null) {
            $this->array[] = $value;
            $this->count = null;
            return;
        }

        if (!is_string($offset) && !is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid dict key: expected a key of type int or string, ' . gettype($offset) . ' given'
            );
        }

        $this->array[$offset] = $value;
        $this->count = null;
    }

    /**
     * @param string|int $offset
     */
    public function offsetUnset($offset): void
    {
        if (!is_string($offset) && !is_int($offset)) {
            throw new InvalidArgumentException(
                'Invalid dict key: expected a key of type int or string, ' . gettype($offset) . ' given'
            );
        }

        unset($this->array[$offset]);
        $this->count = null;
    }

    // -------------------------------------------------------------------------

    /**
     * @return mixed
     */
    public function current()
    {
        if (!$this->valid()) {
            throw new OutOfBoundsException(
                'Out of bounds dict access: invalid index ' . var_export($this->current_key, true)
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
