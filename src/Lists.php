<?php

namespace PHP\Util;

class Lists extends Collection
{
    public function pop()
    {
        return array_pop($this->elements);
    }

    public function push($e)
    {
        return array_push($this->elements, $e);
    }
}
