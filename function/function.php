<?php

use PHP\Util\Collection;

if (!function_exists("collect")) {
    function collect(array $array)
    {
        return new Collection($array);
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
