<?php

namespace FRD\Common;

/**
* This class provides access to common piece of functionality
*/
class CommonFunction
{
    /**
     * Determine if a given array is associative
     * @param  array  $array array to be tested
     * @return boolean
     */
    public static function isAssociativeArray($array)
    {
        return array_keys($array) !== range(0, count($array) - 1);
    }
}
