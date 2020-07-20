<?php

namespace PHP\Util;

class Set extends Collection
{

    public function add($element)
    {
        //check duplicate
        if (!$this->contains($element)) {
            parent::add($element);
        }
    }
}
