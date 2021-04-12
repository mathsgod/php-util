<?php

namespace PHP\Util;

class Lists extends Collection
{
    public function pop()
    {
        $count = $this->elements->count();
        $value = $this->elements->offsetGet($count - 1);
        $this->elements->offsetUnset($count - 1);
        return $value;
    }

    public function push($e)
    {
        $this->elements->append($e);
    }
}
