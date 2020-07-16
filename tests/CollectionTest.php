<?php

declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use PHPUnit\Framework\TestCase;
use PHP\Collection;

final class CollectionTest extends TestCase
{

    public function test_create()
    {
        $collect = new Collection([1, 2, 3]);
        $this->assertInstanceOf(Collection::class, $collect);
    }

    public function test_all()
    {
        $this->assertEquals([1, 2, 3], collect([1, 2, 3])->all());
    }

    public function test_sum()
    {
        $this->assertEquals(6, collect([1, 2, 3])->sum());
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

    public function test_chunk()
    {
        $collect = collect(['a', 'b', 'c', 'd', 'e'])->chunk(2);

        $this->assertEquals([
            ["a", "b"],
            ["c", "d"],
            ["e"]
        ], $collect->all());
    }
    
    
}
