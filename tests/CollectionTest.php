<?php

declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use PHPUnit\Framework\TestCase;
use PHP\Util\Collection;

final class CollectionTest extends TestCase
{

    public function test_create()
    {
        $this->assertInstanceOf(Collection::class, collect([1, 2, 3]));
    }

    public function test_sort()
    {
        $c = collect([3, 1, 2])->sort(function ($a, $b) {
            return $a <=> $b;
        });
        $this->assertEquals([1, 2, 3], $c->all());
    }

    public function test_all()
    {
        $this->assertEquals([1, 2, 3], collect([1, 2, 3])->all());
    }

    public function test_append()
    {
        $collect = collect([1, 2, 3]);
        $collect->add(4);
        $this->assertEquals([1, 2, 3, 4], $collect->all());
    }

    public function test_sum()
    {
        $this->assertEquals(6, collect([1, 2, 3])->sum());
        $this->assertEquals(6, collect([
            ["a" => 1], ["a" => 2], ["a" => 3]
        ])->sum("a"));
    }

    public function test_average()
    {
        $this->assertEquals(2, collect([1, 2, 3])->average());
    }

    public function test_contains()
    {
        $this->assertTrue(collect([1, 2, 3])->contains(1));
        $this->assertFalse(collect([1, 2, 3])->contains(4));
    }


    public function test_diff()
    {
        $this->assertEquals([1, 3, 5], collect([1, 2, 3, 4, 5])->diff([2, 4, 6, 8])->all());
    }

    public function test_splice()
    {
        $this->assertEquals([1, 2], collect([1, 2, 3, 4, 5])->splice(2)->all());
    }

    public function test_map()
    {
        $collect = collect([1, 2, 3])->map(function ($a) {
            return $a + 1;
        });

        $this->assertEquals([2, 3, 4], $collect->all());
    }

    public function test_filter()
    {
        $collect = collect([1, 2, 3])->filter(function ($a) {
            return $a > 1;
        });
        $this->assertEquals([2, 3], $collect->all());
    }

    public function test_clear()
    {
        $collect = collect([1, 2, 3]);
        $collect->clear();
        $this->assertEquals([], $collect->all());
    }

    public function test_remove()
    {
        $collect = collect([1, 2, 3]);
        $collect->remove(2);
        $this->assertEquals([1, 3], $collect->all());
    }

    public function test_chunk()
    {
        $collect = collect(['a', 'b', 'c', 'd', 'e'])->chunk(2);

        $this->assertEquals([
            ["a", "b"],
            ["c", "d"],
            ["e"]
        ], $collect->all());
    }

    public function test_column()
    {
        $this->assertEquals([1, 2, 3], collect([["a" => 1], ["a" => 2], ["a" => 3]])->column("a")->all());
    }

    public function test_join()
    {
        $this->assertEquals("1,2,3", collect([1, 2, 3])->join(","));
    }
}
