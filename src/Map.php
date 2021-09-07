<?php

namespace PHP\Util;

/**
 * @template K
 * @template V
 */
interface Map
{
    /**
     * Removes all of the mappings from this map (optional operation).
     */
    public function clear();

    /**
     * Returns true if this map contains a mapping for the specified key.
     */
    public function containsKey($key): bool;

    /**
     * Returns true if this map maps one or more keys to the specified value.
     */
    public function containsValue($value): bool;

    /**
     * Returns the value to which the specified key is mapped, or null if this map contains no mapping for the key.
     */
    public function get($key);

    /**
     * Returns the value to which the specified key is mapped, or defaultValue if this map contains no mapping for the key.
     */
    public function getOrDefault($key, $value);

    /**
     * Returns true if this map contains no key-value mappings.
     */
    public function isEmpty(): bool;

    /**
     * Associates the specified value with the specified key in this map (optional operation).
     */
    public function put($key, $value);

    /**
     * Copies all of the mappings from the specified map to this map (optional operation).
     */
    public function putAll(Map $m);

    /**
     * Performs the given action for each entry in this map until all entries have been processed or the action throws an exception.
     * @return void
     */
    public function forEach(callable $action);

    /**
     * If the specified key is not already associated with a value or is associated with null, associates it with the given non-null value.
     */
    public function merge($key, $value, callable $remapping_function);


    /**
     * Returns a Set view of the mappings contained in this map.
     * 
     */
    public function entrySet(): Set;
}
