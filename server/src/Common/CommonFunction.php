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

    /**
     * Get value from the $_GET super global
     * @param  key $value
     * @return string
     */
    public static function getValue($value)
    {
        return  htmlspecialchars($_GET[$value]); //TODO: sanitize
    }

    /**
     * Verify is the result from a query is wheather an object or an array, if it is the case
     * then the response would be valid data otherwise not found.
     *
     * @param mix $input input data
     *
     * @return bool
     */
    public static function isValidResponse($input)
    {
        return (is_array($input) && count($input) > 0) || is_object($input);
    }
}
