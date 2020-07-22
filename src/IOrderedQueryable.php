<?php

namespace PHP\Util;

interface IOrderedQueryable extends IQueryable
{
    public function thenBy(callback $key_selector): IOrderedQueryable;
}
