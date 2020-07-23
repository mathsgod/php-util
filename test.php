<?php

require_once("vendor/autoload.php");


$q = collect([
    "grape", "passionfruit", "banana", "apple",
    "orange", "raspberry", "mango", "blueberry"
])->asQueryable();

$aa=$q->orderBy(function ($o) {
    return strlen($o);
})->thenBy(function ($o) {
    return $o;
});
print_r(iterator_to_array($aa));
die();


$a = $q->select(function ($o) {
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
