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

    public function test_get()
    {
        $value = hash_map([1 => "a", 2 => "b", 3 => "c"])->get(2);
        $this->assertEquals("b", $value);
    }

    public function test_clear()
    {
        $map = hash_map([1 => "a", 2 => "b", 3 => "c"]);
        $this->assertEquals(3, $map->size());
        $map->clear();
        $this->assertEquals(0, $map->size());

        $this->assertTrue($map->isEmpty());
    }

    public function test_put()
    {
        $map = hash_map();
        $map->put(1, "a");
        $this->assertEquals(1, $map->size());
        $map->put(2, "a");
        $this->assertEquals(2, $map->size());
        $map->put(2, "b");
        $this->assertEquals(2, $map->size());
    }

    public function test_putIfAbsent()
    {
        $map = hash_map();
        $map->putIfAbsent(1, "a");
        $map->putIfAbsent(1, "b");

        $this->assertEquals("a", $map->get(1));
    }

    public function test_remove()
    {
        $map = hash_map([1 => "a"]);
        $map->remove(1);
        $map->remove(2);
        $this->assertTrue($map->isEmpty());
    }

    public function test_containsKey()
    {
        $map = hash_map();
        $map->put(1, null);
        $this->assertTrue($map->containsKey(1));
    }

    public function test_getOrDefault()
    {
        $map = hash_map([1 => "a"]);
        $this->assertEquals("a", $map->getOrDefault(1, "b"));
        $this->assertEquals("b", $map->getOrDefault(2, "b"));
    }

    public function test_putAll()
    {
        $map = hash_map([1 => "a", 2 => "b"]);
        $map->putAll(hash_map([2 => "x", 3 => "y"]));


        $this->assertEquals(3, $map->size());
        $this->assertEquals("x", $map->get(2));
        $this->assertEquals("y", $map->get(3));
    }

    public function test_merge()
    {
        $map = hash_map([1 => 1, 2 => 2]);
        $map->merge(3, 3, fn ($old, $new) => $old + $new);

        $this->assertEquals(3, $map->get(3));


        $map->merge(3, 10, fn ($old, $new) => $old + $new);
        $this->assertEquals(13, $map->get(3));
    }
}
