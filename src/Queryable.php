<?php

namespace PHP\Util;

use ArrayObject;
use iterable;
use IteratorAggregate;
use Traversable;

class Queryable implements IQueryable
{
    private $source;
    public function __construct(Traversable $source)
    {
        $this->source  = $source;
    }

    public function getIterator()
    {
        return $this->source;
    }

    public function __debugInfo()
    {
        return iterator_to_array($this->source);
    }

    public function first()
    {
        if ($this->count() == 0) {
            throw new InvalidOperationException();
        }
        return iterator_to_array($this->source)[0];
    }

    public function sum()
    {
        return array_sum(iterator_to_array($this->source));
    }

    public function min(callable $callback)
    {
        return array_reduce(iterator_to_array($this->source), function ($result, $item) use ($callback) {
            if (is_null($result)) {
                return $item;
            }
            return ($callback($item) < $callback($result)) ? $item : $result;
        });
    }

    public function max(callable $callback)
    {
        return array_reduce(iterator_to_array($this->source), function ($result, $item) use ($callback) {
            if (is_null($result)) {
                return $item;
            }
            return ($callback($item) >  $callback($result)) ? $item : $result;
        });
    }

    public function average()
    {
        return $this->sum() / $this->count();
    }

    public function count(): int
    {
        return iterator_count($this->source);
    }

    public function all(callable $callback): bool
    {
        $array = array_filter(iterator_to_array($this->source), $callback);
        return count($array) == $this->count();
    }

    public function any(callable $callback): bool
    {
        foreach ($this->source as $item) {
            if ($callback($item)) {
                return true;
            }
        }
        return false;
    }

    public function select(callable $callback): IQueryable
    {
        $ret = new ArrayObject();
        foreach ($this->source as $item) {
            $ret->append($callback($item));
        }
        return new self($ret);
    }

    public function orderBy(callable $callback): IQueryable
    {
        $arr = iterator_to_array($this->source);
        usort($arr, function ($a, $b) use ($callback) {
            $a_value = $callback($a);
            $b_value = $callback($b);
            return $a_value <=> $b_value;
        });
        return new self(new ArrayObject($arr));
    }

    public function orderByDescending(callable $callback): IQueryable
    {
        $arr = iterator_to_array($this->source);
        usort($arr, function ($a, $b) use ($callback) {
            $a_value = $callback($a);
            $b_value = $callback($b);
            return $b_value <=> $a_value;
        });
        return new self(new ArrayObject($arr));
    }

    public function contains($item): bool
    {
        return in_array($item, iterator_to_array($this->source));
    }

    public function distinct(): IQueryable
    {
        return new self(new ArrayObject(array_unique(iterator_to_array($this->source))));
    }

    public function last()
    {
        if ($this->count() == 0) {
            throw new InvalidOperationException();
        }
        return iterator_to_array($this->source)[$this->count() - 1];
    }

    public function prepend($element): IQueryable
    {
        $arr = iterator_to_array($this->source);
        array_unshift($arr, $element);
        return new self(new ArrayObject($arr));
    }

    public function reverse(): IQueryable
    {
        return new self(new ArrayObject(array_values(array_reverse(iterator_to_array($this->source)))));
    }

    public function where(callable $predicate): IQueryable
    {
        return new self(new ArrayObject(array_values(array_filter(iterator_to_array($this->source), $predicate))));
    }

    public function skip(int $count): IQueryable
    {
        $arr = iterator_to_array($this->source);
        $arr = array_slice($arr, $count);
        $arr = array_values($arr);
        return new self(new ArrayObject($arr));
    }

    public function take(int $count): IQueryable
    {
        $arr = iterator_to_array($this->source);
        $arr = array_slice($arr, 0, $count);
        $arr = array_values($arr);
        return new self(new ArrayObject($arr));
    }

    public function takeLast(int $count): IQueryable
    {
        $arr = iterator_to_array($this->source);
        $arr = array_slice($arr, -$count);
        $arr = array_values($arr);
        return new self(new ArrayObject($arr));
    }
}
