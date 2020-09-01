<?php

namespace PHP\Util;

interface QueryInterface
{
    /**
     * @param int|string $limit
     * ust int
     */
    public function limit($limit);

    public function offset(int $offset);

    public function filter(array $filter);

    /**
     * @param array|string $order
     * use array
     */
    public function orderBy($order);
}
