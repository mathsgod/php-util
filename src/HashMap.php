<?php

namespace PHP\Util;

use PHP\Util\AbstractMap\SimpleEntry;

class HashMap implements Map
{
    protected $array;
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * callable function($value,$key)
     */
    public function map(callable $callable): Collection
    {
        $collect = collect([]);
        foreach ($this->array as $key => $value) {

            $collect->add($callable($value, $key));
        }
        return $collect;
    }

    public function get($key)
    {
        return $this->array[$key];
    }

    public function getOrDefault($key, $default)
    {
        if ($this->containsKey($key)) {
            return $this->get($key);
        }
        return $default;
    }

    public function clear()
    {
        $this->array = [];
    }

    public function isEmpty(): bool
    {
        return count($this->array) == 0;
    }

    public function size()
    {
        return count($this->array);
    }


    public function put($key, $value)
    {
        if (isset($this->array[$key])) {
            $old_value = $this->array[$key];
            $this->array[$key] = $value;
            return $old_value;
        } else {
            $this->array[$key] = $value;
            return null;
        }
    }

    public function putIfAbsent($key, $value)
    {

        if (!isset($this->array[$key])) {
            $this->array[$key] = $value;
            return null;
        }
        return $this->array[$key];
    }

    public function remove($key, $value = null)
    {
        if (!isset($value)) {
            $value = $this->array[$key];
            unset($this->array[$key]);
            return $value;
        } else {

            if ($this->array[$key] == $value) {
                unset($this->array[$key]);
                return true;
            }

            return false;
        }
    }

    public function containsKey($key): bool
    {
        return in_array($key, array_keys($this->array));
    }

    public function containsValue($value): bool
    {
        return in_array($value, $this->array);
    }

    public function forEach(callable $callable)
    {
        foreach ($this->array as $key => $value) {
            $callable($key, $value);
        }
    }

    public function values()
    {
        return collect($this->array);
    }

    public function putAll(Map $m)
    {
        $that = $this;
        $m->forEach(function ($key, $value) use ($that) {
            $that->put($key, $value);
        });
    }

    public function merge($key, $value, callable $remapping_function)
    {
        if (!$this->containsKey($key)) {
            $this->put($key, $value);
        } else {
            $value = $remapping_function($this->get($key), $value);
            $this->put($key, $value);
        }
        return $value;
    }

    public function entrySet(): Set
    {
        $set = new Set([]);

        $this->forEach(function ($key, $value) use ($set) {
            $entry = new SimpleEntry($key, $value);
            $set->add($entry);
        });

        return $set;
    }
}
