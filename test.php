<?php

require_once("vendor/autoload.php");


$q = collect([1, 2, 3])->asQueryable();

print_r($q->prepend(0)->take(3)->takeLast(2));
die();


$a=$q->select(function ($o) {
    return [
        "v" => $o
    ];
});


print_r($q->expression);
print_r($a->expression);

echo $a->count();

print_r($a->first());

exit();


$a = new ArrayObject();
$a->append(1);

$b = $a;
$b->append(2);


print_R($a->getArrayCopy());
