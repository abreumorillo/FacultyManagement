<?php

namespace FRD\Model\base;

use FRD\Common\CommonFunction;
use FRD\Common\Exceptions\NotArrayException;
use FRD\Common\Exceptions\NotAssociativeArrayException;
use FRD\DAL\Database;
use FRD\Interfaces\DbModelInterface;

/**
* Base Model class. This class should be extended by  any model class. It provides all the
* common functionalities to interact with the database.
*/
abstract class DbModel implements DbModelInterface
{
    /**
    * Table name
    * @var string
    */
    protected $tableName;

    /**
     * Represent the last inserted id in a given table.
     * @var integer
     */
    protected $lastInsertedId = 0;


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

    /**
     * Get a database row by Id.
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

    public function getAll($fields = '*')
    {
        if(is_array($fields)) {
            $fields = implode(", ", $fields);
        }
        $query = "SELECT ".$fields ." FROM ". $this->tableName;
        return $this->db->query($query);
    }

    /**
     * Get information from the database. this method is pagination ready
     * @param  array  $condition   array containing the condition of the query. So far we only consider one condition. ['key' => 'value']
     * @param  mix  $fields         can be an array of fields or * if not specified
     * @param  integer $page        page
     * @param  integer $itemPerPage  item per page
     * @return mix
     */
    public function get($condition = null, $fields = '*', $page = 1, $itemPerPage = 10)
    {
        $start = ($page -1) * $itemPerPage;
        if (is_array($fields)) {
            $fields = implode(", ", $fields);
        }
        //The condition is used for the query selection criteria
        if ($condition != null && $condition != '*') {
            $conditionKey = array_keys($condition)[0]; //for the moment we only consider one condition
            $conditionValue = $condition[$conditionKey];
            $query = "SELECT ".$fields. " FROM ".$this->tableName." WHERE ".$conditionKey. " = ? LIMIT ? OFFSET ?";
            $params = array($conditionValue, $itemPerPage, $start);
        } else {
            $query = "SELECT ".$fields. " FROM ".$this->tableName. " LIMIT ? OFFSET ?";
            $params = array($itemPerPage, $start);
        }

        return $this->db->query($query, $params);
    }

    /**
     * Save data to the database.
     * @param  array $data  ['key' => value], every key must be a column in the database table
     * @throws NotAssociativeArrayException
     * @return int
     */
    public function post($data)
    {
        if (!CommonFunction::isAssociativeArray($data)) {
            throw new NotAssociativeArrayException("This function expect an associative array");
        }
        $fields = $this->getFields($data);
        $paramasAndPlaceHolder = $this->getParamsAndPlaceHolders($data);
        $query = "INSERT INTO ".$this->tableName. " (". $fields." ) VALUES (". $paramasAndPlaceHolder['placeholders'] .")";

        if ($this->db->noSelect($query, $paramasAndPlaceHolder['params'])) {
            return $this->db->getInsertId();
        }
        return 0;
    }

    /**
     * Update a row in the database.
     * @param  array $condition  indicate the update condition
     * @param  array $data       key=>value representation of the data to update
     * @throws NotAssociativeArrayException
     * @return boolean
     */
    public function put($condition, $data)
    {
        if (!CommonFunction::isAssociativeArray($data)) {
            throw new NotAssociativeArrayException("This function expect an associative array");
        }
        $inputData = $data;
        $fields = $this->getFields($data);
        $conditionKey = array_keys($condition)[0]; //for the moment we only consider one condition
        $conditionValue = $condition[$conditionKey];

        $query = "UPDATE ".$this->tableName." SET ";

        foreach ($data as $key => $value) {
            if (end($data) === $value) {
                $query .= $key." = ? ";
            } else {
                $query .= $key." = ?, ";
            }
        }

        $query .= "WHERE ".$conditionKey." = ".$conditionValue;
        $params = $this->getParamsAndPlaceHolders($data)['params'];

        return $this->db->noSelect($query, $params);
    }

    /**
     * Delete an existing record from the database.
     * @param  array $condition. The condition array so far only take into consideration one condition.
     * @throws NotAssociativeArrayException
     * @return boolean
     */
    public function delete($condition)
    {
        if (!CommonFunction::isAssociativeArray($condition)) {
            throw new NotAssociativeArrayException("This function expect an associative array");
        }

        $conditionKey = array_keys($condition)[0];
        $params[] = $condition[$conditionKey];
        $query = "DELETE FROM ".$this->tableName. " WHERE ".$conditionKey. " = ?";
        return $this->db->noSelect($query, $params);
    }

    /**
     * Execute a SQL query
     * @param  strin $sql sql query
     * @return array|object
     */
    public function query($sql)
    {
        return $this->db-select($sql);
    }

    /**
     * Get the columns name for a query.
     * @param  array $data array of key value pair
     * @param  string $aditionalField
     * @return string comma separated list of string.
     */
    private function getFields($data, $aditionalField = null)
    {
        $fields = implode(", ", array_keys($data));
        if ($aditionalField) {
            $fields .= ", ".$aditionalField;
        }
        return $fields;
    }

    /**
     * Get the parameters and placeholders for a query base on the given data. It generates an array of params
     * and a comma separated list of placeholders based on the amount of parameters.
     * @param  array $data value pair array of data. 'key' => value
     * @return array containing parameters and placeholders
     */
    private function getParamsAndPlaceHolders($data, $aditionalField = null)
    {
        $placeHolders  = "";
        $params = [];

        foreach ($data as $key => $value) {
            $params[] = $this->db->escape($value); //escape params  - good practice
            $placeHolders .= "?, ";
        }

        $placeHolders = rtrim($placeHolders, ", ");

        if ($aditionalField) {
            $placeHolders .= ', ?';
            $this->lastInsertedId = $this->db->getLastInsertedId($this->tableName) + 1;
            $params[] = $this->lastInsertedId; //if not autoincrement
        }
        return [
            'placeholders' => $placeHolders,
            'params' => $params
        ];
    }
    /**
     * Count the number of record in a given table
     * @return int
     */
    public function count()
    {
        return $this->db->count($this->tableName);
    }

}
