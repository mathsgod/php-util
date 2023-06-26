<?php

use PHP\Util\Collection;
use PHP\Util\Lists;
use PHP\Util\HashMap;

if (!function_exists("hash_map")) {
    function hash_map(array $array = [])
    {
        return new HashMap($array);
    }
}

if (!function_exists("collect")) {
    /**
     * @param array|Traversable $array
     */
    function collect($array)
    {
        return new Collection($array);
    }
}

if (!function_exists("lists")) {
    /**
     * @param array|Traversable $array
     */
    function lists($array)
    {
        return new Lists($array);
    }
}

if (!function_exists("var_get")) {
    function var_get($var, string $name)
    {
        if (is_array($var)) {
            return $var[$name];
        }

        if (is_object($var)) {
            return $var->$name;
        }
    }
}
