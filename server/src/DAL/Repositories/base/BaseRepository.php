<?php
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
    protected $db;

    function __construct()
    {
        //Get instance of the database
        $this->db = Database::getInstance();
        //Get the connection to the database
        $this->mysqli = $this->db->getConnection();
    }
}