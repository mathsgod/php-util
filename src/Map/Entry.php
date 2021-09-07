<?php

namespace PHP\Util\Map;

interface Entry
{
    /**
     * Returns the key corresponding to this entry.
     */
    public function getKey();

    /**
     * Returns the value corresponding to this entry.
     */
    public function getValue();

    /**
     * Replaces the value corresponding to this entry with the specified value (optional operation).
     */
    public function setValue($value);
}
