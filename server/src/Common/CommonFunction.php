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

    public static function getRequestMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function getRequestAction()
    {
        return $_GET['action'];
    }

    public static function getValue($value)
    {
        return $_GET[$value];
    }
}
