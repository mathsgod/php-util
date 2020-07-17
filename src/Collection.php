<?php

namespace PHP\Util;

use ArrayIterator;
use Countable;
use Traversable;
use JsonSerializable;
use IteratorAggregate;

class Collection implements IteratorAggregate, Countable, JsonSerializable
{
    private $elements = [];

    public function __construct($array = [])
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }
        $this->elements = array_values($array);
    }

    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }

    public function clear()
    {
        unset($this->elements);
        $this->elements = [];
    }

    public function toArray(): array
    {
        return $this->elements;
    }

    public function add($e)
    {
        $this->elements[] = $e;
    }

    public function contains($e): bool
    {
        return in_array($e, $this->elements);
    }

    public function isEmpty(): bool
    {
        return count($this->elements) == 0;
    }

    public function remove($e)
    {
        $this->elements = array_values(array_diff($this->elements, [$e]));
    }

    public function count()
    {
        return count($this->elements);
    }

    public function all(): array
    {
        return $this->elements;
    }


    private function asCallback($var): callable
    {
        if (is_null($var)) {
            return function ($v) {
                return $v;
            };
        }

        if (is_callable($var)) {
            return $var;
        }

        return function ($item) use ($var) {
            return var_get($item, $var);
        };
    }

    public function average($callback = null)
    {
        return $this->sum($callback) / $this->count();
    }

    public function shuffle(): self
    {
        $array = $this->all();
        shuffle($array);
        return new self($array);
    }


    public function chunk(int $size): self
    {
        return new self(array_chunk($this->all(), $size));
    }

    public function diff(array $array): self
    {
        return new self(array_diff($this->all(), $array));
    }

    public function splice(int $offset, int $length = null): self
    {
        $array = $this->all();
        array_splice($array, $offset, $length ?? $this->count());
        return new self($array);
    }

    public function reduce(callable $callback)
    {
        return array_reduce($this->all(), $callback, 0);
    }

    public function sum($callback = null)
    {
        $callback = $this->asCallback($callback);

        return $this->reduce(function ($result, $item) use ($callback) {
            return $result + $callback($item);
        }, 0);
    }

    public function map(callable $callback): self
    {
        return new self(array_map($callback, $this->all()));
    }

    public function filter(callable $callback): self
    {
        return new self(array_filter($this->all(), $callback));
    }

    public function __debugInfo()
    {
        return $this->all();
    }

    public function jsonSerialize()
    {
        return $this->all();
    }

    public function join(string $glue): string
    {
        return implode($glue, $this->all());
    }

    public function column($column_key): self
    {
        return new self(array_column($this->all(), $column_key));
    }
}
