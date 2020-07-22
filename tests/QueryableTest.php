<?php

declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use PHP\Util\IQueryable;
use PHPUnit\Framework\TestCase;

final class QueryableTest extends TestCase
{
    public function test_asQueryable()
    {
        $q = collect([1, 2, 3])->asQueryable();

        $this->assertInstanceOf(IQueryable::class, $q);
    }

    public function test_sum()
    {
        $this->assertEquals(6, collect([1, 2, 3])->asQueryable()->sum());
    }

    public function test_min()
    {
        $this->assertEquals(1, collect([1, 2, 3])->asQueryable()->min(function ($o) {
            return $o;
        }));
    }

    public function test_max()
    {
        $this->assertEquals(3, collect([1, 2, 3])->asQueryable()->max(function ($o) {
            return $o;
        }));
    }

    public function test_count()
    {
        $this->assertEquals(3, collect([1, 2, 3])->asQueryable()->count());
    }

    public function test_average()
    {
        $this->assertEquals(2, collect([1, 2, 3])->asQueryable()->average());
    }

    public function test_all()
    {
        $ret = collect([1, 2, 3])->asQueryable()->all(function ($o) {
            return $o > 0;
        });

        $this->assertTrue($ret);

        $ret = collect([1, 2, 3])->asQueryable()->all(function ($o) {
            return $o > 5;
        });
        $this->assertFalse($ret);
    }

    public function test_any()
    {
        $ret = collect([1, 2, 3])->asQueryable()->any(function ($o) {
            return $o >= 3;
        });

        $this->assertTrue($ret);

        $ret = collect([1, 2, 3])->asQueryable()->any(function ($o) {
            return $o > 5;
        });
        $this->assertFalse($ret);
    }

    public function test_first()
    {
        $this->assertEquals(1, collect([1, 2, 3])->asQueryable()->first());
    }

    public function test_select()
    {
        $q = collect([1, 2, 3])->asQueryable()->select(function ($o) {
            return [
                "value" => $o
            ];
        });

        $this->assertEquals(["value" => 1], $q->first());
    }

    public function test_orderBy()
    {
        $q = collect([2, 1, 3])->asQueryable()->orderBy(function ($o) {
            return $o;
        });

        $this->assertEquals(1, $q->first());
    }

    public function test_orderByDescending()
    {
        $q = collect([2, 1, 3])->asQueryable()->orderByDescending(function ($o) {
            return $o;
        });

        $this->assertEquals(3, $q->first());
    }

    public function test_contains()
    {
        $this->assertTrue(collect([2, 1, 3])->asQueryable()->contains(3));
        $this->assertFalse(collect([2, 1, 3])->asQueryable()->contains(5));
    }

    public function test_distinct()
    {
        $this->assertEquals(3, collect([1, 1, 2, 3, 3])->asQueryable()->distinct()->count());
    }

    public function test_last()
    {
        $this->assertEquals(3, collect([1,  2, 3])->asQueryable()->last());
    }

    public function test_prepend()
    {
        $q = collect([1, 2, 3])->asQueryable()->prepend(0);
        $this->assertEquals(4, $q->count());
        $this->assertEquals(0, $q->first());
    }

    public function test_reverse()
    {
        $q = collect([1, 2, 3])->asQueryable()->reverse();
        $this->assertEquals([3, 2, 1], iterator_to_array($q));
    }
}
