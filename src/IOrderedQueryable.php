<?php

namespace PHP\Util;

interface IOrderedQueryable extends IQueryable
{
    public function thenBy(callable $key_selector): IOrderedQueryable;
    public function thenByDescending(callable $key_selector): IOrderedQueryable;
}
