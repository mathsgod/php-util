<?php

require_once("vendor/autoload.php");


$q = collect([1, 2, 3])->asQueryable();


$q->select(function ($o) {
    return [
        "v" => $o
    ];
});

echo $q->count();

print_r($q->first());

exit();


$a = new ArrayObject();
$a->append(1);

$b = $a;
$b->append(2);


print_R($a->getArrayCopy());
