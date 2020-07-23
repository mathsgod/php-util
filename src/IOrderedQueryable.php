<?php

namespace PHP\Util;

interface IOrderedQueryable extends IQueryable
{
    public function thenBy(callable $key_selector): IOrderedQueryable;
}
