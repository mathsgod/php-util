<?php

namespace PHP\Util;

class OrderedQueryable extends Queryable implements IOrderedQueryable
{
    public function thenBy(callback $key_selector): IOrderedQueryable
    {
    }
}
