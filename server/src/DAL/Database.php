<?php

/**
 * Purpose  : This class provide access to the database. So far this class provides the following functionalities:
 *            execute paremeterized queries, execute transactions. We followed the Singleton design pattern. This class follow this approach https://       gist.github.com/jonashansen229/4534794
 * Date     : 3/8/2015
 * @author  : Neris Sandino Abreu
 */

namespace FRD\DAL;

class Database
{
    /**
     * Database connection information
     */
    private $_hostName;
    private $_username;
    private $_password;
    private $_database;

    /**
     * The SQL query to be prepared
     * @var string
     */
    protected $_query;

    /**
     * Last performed query
     * @var string
     */
    protected $_lastQuery;

    /**
     * Variable to  hold last statement error.
     * @var string
     */
    protected $_stmtError;

    /**
     * Dynamic array that holds a combination of where condition/table data value types and parameter references
     * @var array
     */
    protected $_bindParams = array('');

    /**
     * Return type: 'Array' to return results as array, 'Object' as object
     * 'Json' as json string
     *
     * @var string
     */
    public $returnType = 'Object';

    /**
     * Flag to test if a transaction is in progress
     * @var boolean
     */
    protected $_transaction_in_progress = false;

    /**
     * The last inserted ID if where are using autoincrement
     * @var null
     */
    protected $_lastInsertId = null;

    /**
     * use to store the number of affected row in a given query
     * @var integer
     */
    public $count = 0;

    /**
     * connection object
     * @var mysqli connection
     */
    private $_connection;

    /**
     * Instance of the current class
     * @var Database
     */
    private static $_instance;

    /**
     * Get an instance of the Database.
     * @return Database instance.
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Class Constructor. It initializes the database connection.
     */
    private function __construct()
    {
        $this->_hostName = getenv('HOSTNAME');
        $this->_password = getenv('PASSWORD');
        $this->_username = getenv('USERNAME');
        $this->_database = getenv('DATABASE');

        //development connection
        $this->_connection = new \mysqli($this->_hostName, $this->_username, $this->_password, $this->_database);

        // Error handling.
        if (mysqli_connect_error()) {
            trigger_error('Failed to connect to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);
        }
    }

    /**
     * Empty clone magic method to prevent duplication.
     */
    private function __clone()
    {
    }

    /**
     * Get the current mysqli connection.
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * Return the last executed query.
     * @return string
     */
    public function getLastQuery()
    {
        return $this->_lastQuery;
    }

    /**
     * Function for Select type queries.
     * @param  string $query
     * @param  array $bindParams
     * @return mix
     */
    public function query($query, $bindParams = null)
    {
        return $this->rawQuery($query, $bindParams);
    }

    /**
     * Function for executing Insert, Update or Delete operation.
     * @param  string $query
     * @param  array $bindParams  array of params
     * @return boolean
     */
    public function noSelect($query, $bindParams = null)
    {
        $this->rawQuery($query, $bindParams);
        if ($this->count > 0) {
            return true;
        }
        return false;
    }

    /**
    * Method returns the last mysql error.
    *
    * @return string
    */
    public function getLastError()
    {
        if (!$this->_connection) {
            return "mysqli is null";
        }
        return trim($this->_stmtError . " " . $this->_connection->error);
    }

    /**
    * Begin a transaction.
    *
    * @uses mysqli->autocommit(false)
    */
    public function startTransaction()
    {
        $this->_connection->autocommit(false);
        $this->_transaction_in_progress = true;
    }

    /**
    * Transaction commit
    *
    * @uses mysqli->commit();
    * @uses mysqli->autocommit(true);
    */
    public function commit()
    {
        $this->_connection->commit();
        $this->_transaction_in_progress = false;
        $this->_connection->autocommit(true);
    }

    /**
    * Transaction rollback function
    *
    * @uses mysqli->rollback();
    * @uses mysqli->autocommit(true);
    */
    public function rollback()
    {
        $this->_connection->rollback();
        $this->_transaction_in_progress = false;
        $this->_connection->autocommit(true);
    }

    /**
    * This methods returns the ID of the last inserted item. This method is useful when the table has auto-increment PK.
    *
    * @return integer The last inserted item ID.
    */
    public function getInsertId()
    {
        return $this->_connection->insert_id;
    }

    /**
    * Escape harmful characters which might affect a query.
    *
    * @param string $str The string to escape.
    * @return string The escaped string.
    */
    public function escape($str)
    {
        return $this->_connection->real_escape_string($str);
    }

    /**
     * Get the id of the last inserted record in  a given table. This method is useful when we are not using  auto-increment.
     * @param  string $tableName       name of the table
     * @param  string $primaryKeyColum  primary key column
     * @return int The last inserted id in a given table.
     */
    public function getLastInsertedId($tableName, $primaryKeyColum = 'id')
    {
        $query = "SELECT $primaryKeyColum AS lastId FROM $tableName ORDER BY $primaryKeyColum DESC LIMIT 1";
        $result = $this->rawQuery($query);
        if ($result) {
            return $result->lastId;
        }
        return 0;
    }

    /**
     * This perform the raw query operation. It builds the query dynamically using prepare statement
     * @param  string $query
     * @param  array $bindParams
     * @return array containing returned objects from the database.
     */
    private function rawQuery($query, $bindParams = null)
    {
        $params = array('');
        $this->_query = $query;
        $stmt = $this->_prepareQuery();

        if (is_array($bindParams) === true) {
            foreach ($bindParams as $prop => $val) {
                $params[0] .= $this->_determineType($val);
                array_push($params, $bindParams[$prop]);
            }
            call_user_func_array(array($stmt, 'bind_param'), $this->refValues($params));
        }

        $stmt->execute();
        $this->count = $stmt->affected_rows; //
        $this->_stmtError = $stmt->error;
        $this->_lastQuery = $this->replacePlaceHolders($this->_query, $params); //
        $res = $this->_dynamicBindResults($stmt);
        $this->reset();
        return $res;
    }

    /**
    * Method attempts to prepare the SQL query
    * @throws an error if there was a problem.
    * @return mysqli_stmt
    */
    protected function _prepareQuery()
    {
        if (!$stmt = $this->_connection->prepare($this->_query)) {
            throw new \Exception("Problem preparing query ($this->_query) " . $this->_connection->error);
        }
        return $stmt;
    }

    /**
    * This method is needed for prepared statements. They require
    * the data type of the field to be bound with "i" s", etc.
    * This function takes the input, determines what type it is,
    * and then updates the param_type.
    *
    * @param mixed $item Input to determine the type.
    * @return string The joined parameter types.
    */
    protected function _determineType($item)
    {
        switch (gettype($item)) {
            case 'NULL':
            case 'string':
            return 's';
            break;

            case 'boolean':
            case 'integer':
            return 'i';
            break;

            case 'blob':
            return 'b';
            break;

            case 'double':
            return 'd';
            break;
        }
        return '';
    }

    /**
    * Function to replace ? with variables from bind variable
    * @param string $str
    * @param Array $vals
    * @return string
    */
    protected function replacePlaceHolders($str, $vals)
    {
        $i = 1;
        $newStr = "";

        while ($pos = strpos($str, "?")) {
            $val = $vals[$i++];
            if (is_object($val)) {
                $val = '[object]';
            }
            if ($val === null) {
                $val = 'NULL';
            }
            $newStr .= substr($str, 0, $pos) . "'". $val . "'";
            $str = substr($str, $pos + 1);
        }
        $newStr .= $str;
        return $newStr;
    }

    /**
    * This helper method takes care of prepared statements' "bind_result method
    * when the number of variables to pass is unknown.
    *
    * @param mysqli_stmt $stmt Equal to the prepared statement object.
    * @return array The results of the SQL fetch.
    */
    protected function _dynamicBindResults(\mysqli_stmt $stmt)
    {
        $parameters = array();
        $results = array();
            // See http://php.net/manual/en/mysqli-result.fetch-fields.php
        $mysqlLongType = 252;
        $shouldStoreResult = false;

        $meta = $stmt->result_metadata();

            // if $meta is false yet sqlstate is true, there's no sql error but the query is
            // most likely an update/insert/delete which doesn't produce any results
        if (!$meta && $stmt->sqlstate) {
            return array();
        }

        $row = array();
        while ($field = $meta->fetch_field()) {
            if ($field->type == $mysqlLongType) {
                $shouldStoreResult = true;
            }

            $row[$field->name] = null;
            $parameters[] = & $row[$field->name];
        }

        // https://github.com/joshcam/PHP-MySQLi-Database-Class/pull/119
        if ($shouldStoreResult) {
            $stmt->store_result();
        }

        call_user_func_array(array($stmt, 'bind_result'), $parameters);

        $this->totalCount = 0;
        $this->count = 0;
        while ($stmt->fetch()) {
            if ($this->returnType == 'Object') {
                $x = new \stdClass();
                foreach ($row as $key => $val) {
                    if (is_array($val)) {
                        $x->$key = new \stdClass();
                        foreach ($val as $k => $v) {
                            $x->$key->$k = $v;
                        }
                    } else {
                        $x->$key = $val;
                    }
                }
            } else {
                $x = array();
                foreach ($row as $key => $val) {
                    if (is_array($val)) {
                        foreach ($val as $k => $v) {
                            $x[$key][$k] = $v;
                        }
                    } else {
                        $x[$key] = $val;
                    }
                }
            }
            $this->count++;
            array_push($results, $x);
        }
        if ($shouldStoreResult) {
            $stmt->free_result();
        }
        $stmt->close();
        if (count($results) == 1) {
            return $results[0];
        }

        return $results;
    }

    /**
     * According tothe PHP documentation value to bind_params should be passed by reference.
     * Note Section PHP: http://php.net/manual/en/mysqli-stmt.bind-param.php
    * @param array $arr.
    * @return array
    */
    protected function refValues(Array &$arr)
    {
        //Reference in the function arguments are required for HHVM to work
        //https://github.com/facebook/hhvm/issues/5155
        //Referenced data array is required by mysqli since PHP 5.3+
        if (strnatcmp(phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach ($arr as $key => $value) {
                $refs[$key] = & $arr[$key];
            }
            return $refs;
        }
        return $arr;
    }

    /**
    * Reset states after an execution of any database operation.
    */
    protected function reset()
    {
        $this->_bindParams = array(''); // Create the empty 0 index
        $this->_query = null;
        $this->returnType = 'Object';
        $this->_lastInsertId = null;
    }
}
