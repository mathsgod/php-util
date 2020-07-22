<?php

require_once("vendor/autoload.php");


$a = new ArrayObject();
$a->append(1);

$b = $a;
$b->append(2);


print_R($a->getArrayCopy());