<?php

if(count(get_included_files()) ==1) exit("Direct access not permitted.");
/**
 * Purpose          : This class is used to provide the common functionality for repositories that need to access the database.
 * Date             : 3/14/2015
 * @author          ; Neris S. Abreu
 */

namespace FRD\DAL\Repositories\base;
use FRD\DAL\Database;

abstract class BaseRepository {
    //This property is declared protected so that subclasses can have access to it.
    protected $mysqli;

    function __construct()
    {
        //Get instance of the database
        $db = Database::getInstance();
        //Get the connection to the database
        $this->mysqli = $db->getConnection();
    }
}