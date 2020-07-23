<?php

declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use PHP\Util\IQueryable;
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
    }
}
