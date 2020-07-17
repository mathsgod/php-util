<?php

require_once("vendor/autoload.php");
print_R(collect([["a" => 1], ["a" => 2], ["a" => 3]])->all());

$list=lists([1,2,3]);