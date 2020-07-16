<?php

use PHP\Collection;

if (!function_exists("collect")) {
    function collect(array $array)
    {
        return new Collection($array);
    }
}
