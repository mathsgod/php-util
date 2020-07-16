<?php

namespace PHP;

use ArrayIterator;

class Collection extends ArrayIterator
{
    public function all(): array
    {
        return iterator_to_array($this);
    }

    public function average()
    {
        return $this->sum() / $this->count();
    }

    public function sum($callback = null)
    {
        if (is_null($callback)) {
            $callback = function ($value) {
                return $value;
            };
        } elseif (!is_callable($callback)) {
            $callback = function(){
             //   return 
            }
        }

        return $this->reduce(function ($result, $item) use ($callback) {
            return $result + $callback($item);
        }, 0);
    }

    public function reduce(callable $callback)
    {
        return array_reduce($this->all(), $callback, 0);
    }

    public function contains($element): bool
    {
        foreach ($this as $e) {
            if ($e == $element) {
                return true;
            }
        }
        return false;
    }

    public function diff(array $array): self
    {
        return new self(array_values(array_diff($this->all(), $array)));
    }

    public function splice(int $offset, int $length = null): self
    {
        $array = $this->all();
        array_splice($array, $offset, $length ?? $this->count());
        return new self(array_values($array));
    }

    public function shuffle(): self
    {
        $array = $this->all();
        shuffle($array);
        return new self($array);
    }

    public function map(callable $callback): self
    {
        return new self(array_map($callback, $this->all()));
    }

    public function filter(callable $callback): self
    {
        return new self(array_values(array_filter($this->all(), $callback)));
    }

    public function chunk(int $size): self
    {
        return new self(array_chunk($this->all(), $size));
    }
}
