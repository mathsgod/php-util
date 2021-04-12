<?php

namespace PHP\Util;

use ArrayObject;
use Countable;
use Traversable;
use JsonSerializable;
use IteratorAggregate;

class Collection implements IteratorAggregate, Countable, JsonSerializable
{
    protected $elements = null;

    public function __construct($array = [])
    {
        if ($array instanceof Traversable) {
            $array = iterator_to_array($array);
        }
        $this->elements = new ArrayObject(array_values($array));
    }

    public function getIterator()
    {
        return $this->elements->getIterator();
    }

    public function clear()
    {
        $this->elements->exchangeArray([]);
    }

    public function toArray(): array
    {
        return $this->elements->getArrayCopy();
    }

    public function add($e)
    {
        $this->elements->append($e);
    }

    public function contains($e): bool
    {
        return in_array($e, $this->toArray());
    }

    public function isEmpty(): bool
    {
        return $this->elements->count() == 0;
    }

    public function remove($e)
    {
        $array = $this->elements->getArrayCopy();
        if (is_object($e)) {
            $array = array_values(array_udiff($array, [$e], function ($a, $b) {
                return $a <=> $b;
            }));
        } else {
            $array = array_values(array_diff($array, [$e]));
        }
        $this->elements->exchangeArray($array);
    }

    public function count()
    {
        return $this->elements->count();
    }

    public function all(): array
    {
        return $this->elements->getArrayCopy();
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

    public function asQueryable(): IQueryable
    {
        return new Queryable($this->elements);
    }

    public function sort(callable $callback): Lists
    {
        $array = $this->all();
        usort($array, $callback);
        return new Lists($array);
    }

    public function merge(array $arrays): self
    {
        return new self(array_merge($this->all(), $arrays));
    }
}
