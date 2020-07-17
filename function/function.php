<?php

use PHP\Util\Collection;
use PHP\Util\Lists;

if (!function_exists("collect")) {
    function collect(array $array)
    {
        return new Collection($array);
    }
}

if (!function_exists("lists")) {
    function lists(array $array)
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
