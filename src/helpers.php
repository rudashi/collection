<?php

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