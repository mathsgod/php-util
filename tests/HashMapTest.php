<?php

declare(strict_types=1);
error_reporting(E_ALL & ~E_WARNING);

use PHPUnit\Framework\TestCase;
use PHP\Util\Collection;

final class HashMapTest extends TestCase
{
    public function test_map()
    {
        $a = hash_map([1 => "a", 2 => "b", 3 => "c"])->map(function ($item, $index) {
            return ["key" => $index, "value" => $item];
        })->toArray();
        $this->assertEquals([
            ["key" => 1, "value" => "a"],
            ["key" => 2, "value" => "b"],
            ["key" => 3, "value" => "c"]

        ], $a);
    }
}
