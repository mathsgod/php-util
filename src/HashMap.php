<?php

namespace PHP\Util;

class HashMap
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

    public function clear()
    {
        $this->array = [];
    }

    public function isEmpty()
    {
        return count($this->array) == 0;
    }

    public function size()
    {
        return count($this->array);
    }


    /**
     * 如果插入的 key 對應的 value 已經存在，則執行 value 替換操作，返回舊的 value 值，如果不存在則執行插入，返回 null。
     */
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


    /**
     * 會先判斷指定的鍵（key）是否存在，不存在則將鍵/值對插入到 HashMap 中。
     */
    public function putIfAbsent($key, $value)
    {

        if (!isset($this->array[$key])) {
            $this->array[$key] = $value;
            return null;
        }
        return $this->array[$key];
    }

    /**
     * 如果指定 key，返回指定鍵 key 關聯的值，如果指定的 key 映射值為 null 或者該 key 並不存在於該 HashMap 中，此方法將返回null。
     * 如果指定了 key 和 value，刪除成功返回 true，否則返回 false。
     */
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

    public function containsKey($key)
    {
        return isset($this->array[$key]);
    }
}
