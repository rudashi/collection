<?php

use Rudashi\Set;
use Rudashi\Map;

if (!function_exists('map')) {
    /**
     * Create a Map from the given value.
     *
     * @param  mixed  $value
     * @return Map
     */
    function map($value = null): Map
    {
        return new Map($value);
    }
}

if (!function_exists('set')) {
    /**
     * Create a Set from the given value.
     *
     * @param  mixed  $value
     * @return Set
     */
    function set($value = null): Set
    {
        return new Set($value);
    }
}