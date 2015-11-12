<?php

namespace FRD\Model\base;

use FRD\DAL\Database;
use FRD\Interfaces\DbModelInterface;

/**
* Base Model class
*/
abstract class DbModel implements DbModelInterface
{
    /**
    * Table name
    * @var string
    */
    protected $tableName;
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

    // public function getById($id, $fields = '*');
    // public function get($condiction = null, $fields = '*', $limit = null, $offset = null);
    // public function post($tableName, $data);
    // public function put($tableName, $condiction, $data);
    // public function delete($tableName, $condiction);
    // public function query($sql);

    /**
     * Get a database row by Id
     * @param  int $id
     * @param  mix $fields
     * @return object
     */
    public function getById($id, $fields = '*')
    {
        if (is_array($fields)) {
            $fields = implode(", ", $fields);
        }
        $query = "SELECT ".$fields. " FROM ".$this->tableName." WHERE id = ?";
        $params = array($id);
        return $this->db->query($query, $params);
    }
}
