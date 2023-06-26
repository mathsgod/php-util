<?php

declare(strict_types=1);


use PHPUnit\Framework\TestCase;

final class OrderedQueryableTest extends TestCase
{
    public function test_thenBy()
    {
        $q = collect([
            "grape", "passionfruit", "banana", "apple",
            "orange", "raspberry", "mango", "blueberry"
        ])->asQueryable();

        $a = $q->orderBy(function ($o) {
            return strlen($o);
        })->thenBy(function ($o) {
            return $o;
        });

        $this->assertEquals(["apple", "grape", "mango", "banana", "orange", "blueberry", "raspberry", "passionfruit"], iterator_to_array($a));


        $b = $q->orderByDescending(function ($o) {
            return strlen($o);
        })->thenBy(function ($o) {
            return $o;
        });

        $this->assertEquals(["passionfruit",  "blueberry", "raspberry", "banana", "orange", "apple", "grape", "mango"], iterator_to_array($b));

        $c = $q->orderBy(function ($o) {
            return strlen($o);
        })->thenByDescending(function ($o) {
            return $o;
        });
        $this->assertEquals(["mango", "grape", "apple",  "orange", "banana", "raspberry", "blueberry", "passionfruit"], iterator_to_array($c));

        $d = $q->orderByDescending(function ($o) {
            return strlen($o);
        })->thenByDescending(function ($o) {
            return $o;
        });
        $this->assertEquals(["passionfruit",   "raspberry", "blueberry",  "orange", "banana", "mango", "grape", "apple"], iterator_to_array($d));
    }
}
