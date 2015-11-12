<?php
/**
 * Purpose          : This class is used to provide the common functionality for repositories that need to access the database.
 * Date             : 3/14/2015
 * @author          ; Neris S. Abreu
 */

namespace FRD\DAL\Repositories\base;

use FRD\DAL\Database;

abstract class BaseRepository
{

    /**
     * Current active connection
     * @var mysqli connection
     */
    protected $connection;

    /**
     * Instance of the database connection
     * @var Database
     */
    protected $db;

    /**
     * Initializes the database connection
     */
    public function __construct()
    {
        //Get instance of the database
        $this->db = Database::getInstance();
        //Get the connection to the database
        $this->connection = $this->db->getConnection();
    }

}
