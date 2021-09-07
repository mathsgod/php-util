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
}
