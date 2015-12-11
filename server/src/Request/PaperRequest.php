<?php

namespace FRD\Request;

/**
* PaperRequest class
*/
abstract class PaperRequest
{
    /**
     * Array containing the user data formated to be processed in the database
     * @var array
     */
    protected $userData = [];
    protected $isInsert = false;

    /**
     * Array containing the array of error if any validation error.
     * @var array
     */
    protected $validationErrors = [];
    //title, abstract, citation
    // function __construct(argument)
    // {
    //     # code...
    // }
}
