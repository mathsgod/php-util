<?php

declare(strict_types=1);
error_reporting(E_ALL & ~E_WARNING);

use PHPUnit\Framework\TestCase;
use PHP\Util\Lists;

final class ListsTest extends TestCase
{
    public function test_pop()
    {
        $l = new Lists();
        $l->push(1);
        $l->push(2);
        $l->push(3);

        $this->assertEquals(3, $l->pop());
    }
}
