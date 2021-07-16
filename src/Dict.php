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

        throw new OutOfBoundsException('Out of bounds dict access: invalid index ' . var_export($offset));
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
                'Out of bounds dict access: invalid index ' . var_export($this->current_key)
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
