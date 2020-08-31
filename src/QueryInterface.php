<?php

namespace PHP\Util;

interface QueryInterface
{
    public function limit(int $limit);
    public function offset(int $offset);
    public function filter(array $filter);

    /**
     * @param array|string $order
     * suggest use array
     */
    public function orderBy($order);
}
