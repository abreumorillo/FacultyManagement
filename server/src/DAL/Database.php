<?php


if(count(get_included_files()) ==1) exit("Direct access not permitted.");
/**
 * Purpose  : This class provide access to the database. this class follow this approach
 *            https://gist.github.com/jonashansen229/4534794
 * Date     : 3/8/2015
 * @author  : Neris Sandino Abreu
 */

namespace FRD\DAL;

class Database {
    /**
     * Database connection information
     */
    private $_hostName = "localhost";
    private $_username = "root";
    private $_password = "root";
    private $_database = "research";

    private $_connection;
    // Store the single instance.
    private static $_instance;

    /**
     * Get an instance of the Database.
     * @return Database
     */
    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Class Constructor.
     */
    private function __construct() {
        //require the file with the necesary information for connecting to the database
        //require_once "/home/nsa2741/db_conn.php"; //$hostname, $username, $password, $database
        //$this->_connection = new mysqli($hostname, $username, $password, $database);

        //development connection
        $this->_connection = new mysqli($this->_hostName, $this->_username, $this->_password, $this->_database);

        // Error handling.
        if (mysqli_connect_error()) {
            trigger_error('Failed to connect to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);
        }
    }

    /**
     * Empty clone magic method to prevent duplication.
     */
    private function __clone() {
    }

    /**
     * Get the mysqli connection.
     */
    public function getConnection() {
        return $this->_connection;
    }

}

