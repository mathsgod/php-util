<?php

namespace PHP\Util;

use ArrayObject;
use Traversable;

use function PHPSTORM_META\map;

class Queryable implements IQueryable
{
    private $source;
    public $expression = [];
    public function __construct(Traversable $source)
    {
        $this->source  = $source;
    }

    public function getIterator()
    {
        $source = $this->source;
        foreach ($this->expression as $expression) {
            switch ($expression["type"]) {
                case "select":
                    $new_source = new ArrayObject();
                    $selector = $expression["params"]["selector"];
                    foreach ($source as $item) {
                        $new_source->append($selector($item));
                    }
                    $source = $new_source;
                    break;
                case "orderBy":
                    $arr = iterator_to_array($source);
                    $key_selector = $expression["params"]["key_selector"];
                    usort($arr, function ($a, $b) use ($key_selector) {
                        $a_value = $key_selector($a);
                        $b_value = $key_selector($b);
                        return $a_value <=> $b_value;
                    });
                    $source = new ArrayObject($arr);
                    break;
                case "orderByDescending":
                    $arr = iterator_to_array($source);
                    $key_selector = $expression["params"]["key_selector"];
                    usort($arr, function ($a, $b) use ($key_selector) {
                        $a_value = $key_selector($a);
                        $b_value = $key_selector($b);
                        return $b_value <=> $a_value;
                    });
                    $source = new ArrayObject($arr);
                    break;
                case "distinct":
                    $source = new ArrayObject(array_unique(iterator_to_array($source)));
                    break;
                case "prepend":
                    $arr = iterator_to_array($source);
                    array_unshift($arr, $expression["params"]["element"]);
                    $source = new ArrayObject($arr);
                    break;
                case "reverse":
                    $source = new ArrayObject(array_values(array_reverse(iterator_to_array($source))));
                    break;
                case "where":
                    $predicate = $expression["params"]["predicate"];
                    $source = new ArrayObject(array_values(array_filter(iterator_to_array($source), $predicate)));
                    break;
                case "skip":
                    $count = $expression["params"]["count"];
                    $arr = iterator_to_array($source);
                    $arr = array_slice($arr, $count);
                    $arr = array_values($arr);
                    $source = new ArrayObject($arr);
                    break;
                case "take":
                    $count = $expression["params"]["count"];
                    $arr = iterator_to_array($source);
                    $arr = array_slice($arr, 0, $count);
                    $arr = array_values($arr);
                    $source = new ArrayObject($arr);
                    break;
                case "takeLast":
                    $count = $expression["params"]["count"];
                    $arr = iterator_to_array($source);
                    $arr = array_slice($arr, -$count);
                    $arr = array_values($arr);
                    $source = new ArrayObject($arr);
                    break;
            }
        }
        return $source;
    }

    public function createQuery($expression): IQueryable
    {
        $q = new self($this->source);
        $q->expression = $expression;
        return $q;
    }

    public function __debugInfo()
    {
        return iterator_to_array($this);
    }

    public function first()
    {
        if ($this->count() == 0) {
            throw new InvalidOperationException();
        }
        return iterator_to_array($this)[0];
    }

    public function sum()
    {
        return array_sum(iterator_to_array($this));
    }

    public function min(callable $callback)
    {
        return array_reduce(iterator_to_array($this), function ($result, $item) use ($callback) {
            if (is_null($result)) {
                return $item;
            }
            return ($callback($item) < $callback($result)) ? $item : $result;
        });
    }

    public function max(callable $callback)
    {
        return array_reduce(iterator_to_array($this), function ($result, $item) use ($callback) {
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
        return iterator_count($this);
    }

    public function all(callable $callback): bool
    {
        $array = array_filter(iterator_to_array($this), $callback);
        return count($array) == $this->count();
    }

    public function any(callable $callback): bool
    {
        foreach ($this as $item) {
            if ($callback($item)) {
                return true;
            }
        }
        return false;
    }

    public function select(callable $selector): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "select",
            "params" => ["selector" => $selector]
        ];
        return $this->createQuery($exp);
    }

    public function orderBy(callable $key_selector): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "orderBy",
            "params" => [
                "key_selector" => $key_selector
            ]
        ];
        return $this->createQuery($exp);
    }

    public function orderByDescending(callable $key_selector): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "orderByDescending",
            "params" => [
                "key_selector" => $key_selector
            ]
        ];
        return $this->createQuery($exp);
    }

    public function contains($item): bool
    {
        return in_array($item, iterator_to_array($this->source));
    }

    public function distinct(): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "distinct"
        ];
        return $this->createQuery($exp);
    }

    public function last()
    {
        if ($this->count() == 0) {
            throw new InvalidOperationException();
        }
        return iterator_to_array($this)[$this->count() - 1];
    }

    public function prepend($element): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "prepend",
            "params" => [
                "element" => $element
            ]
        ];
        return $this->createQuery($exp);
    }

    public function reverse(): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "reverse"
        ];
        return $this->createQuery($exp);
    }

    public function where(callable $predicate): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "where",
            "params" => [
                "predicate" => $predicate
            ]
        ];
        return $this->createQuery($exp);
    }

    public function skip(int $count): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "skip",
            "params" => [
                "count" => $count
            ]
        ];
        return $this->createQuery($exp);
    }

    public function take(int $count): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "take",
            "params" => ["count" => $count]
        ];
        return $this->createQuery($exp);
    }

    public function takeLast(int $count): IQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "takeLast",
            "params" => ["count" => $count]
        ];
        return $this->createQuery($exp);
    }
}
