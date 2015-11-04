<?php

// if(count(get_included_files()) ==1) exit("Direct access not permitted.");
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
    private $_password = "secret";
    private $_database = "research";

    /**
     * The SQL query to be prepared 
     * @var string
     */
    protected $_query;
    protected $_lastQuery;

    /**
     * Array to hold where conditions
     * @var array
     */
    protected $_where = array();

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

    protected $_transaction_in_progress = false;
    protected $_lastInsertId = null;
    public $count = 0;
    /**
     * Key field for Map()'ed result array
     * @var string
     */
    // protected $_mapKey = null;

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
        $this->_connection = new \mysqli($this->_hostName, $this->_username, $this->_password, $this->_database);

        // Error handling.
        if (mysqli_connect_error()) {
            trigger_error('Failed to connect to MySQL: ' . mysqli_connect_error(), E_USER_ERROR);
        }
    }

        /**
     * Reset states after an execution
     *
     * @return object Returns the current instance.
     */
        protected function reset()
        {

            $this->_where = array();
        // $this->_having = array();
        // $this->_join = array();
        // $this->_orderBy = array();
        // $this->_groupBy = array();
        $this->_bindParams = array(''); // Create the empty 0 index
        $this->_query = null;
        // $this->_queryOptions = array();
        $this->returnType = 'Object';
        // $this->_nestJoin = false;
        // $this->_forUpdate = false;
        // $this->_lockInShareMode = false;
        $this->_tableName = '';
        $this->_lastInsertId = null;
        // $this->_updateColumns = null;
        // $this->_mapKey = null;
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

    public function rawQuery($query, $bindParams = null)
    {
        $params = array('');
        $this->_query = $query;
        $stmt = $this->_prepareQuery();

        if(is_array($bindParams) === true) 
        {
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
     * A convenient SELECT * function.
     *
     * @param string  $tableName The name of the database table to work with.
     * @param integer|array $numRows Array to define SQL limit in format Array ($count, $offset)
     *                               or only $count
     *
     * @return array Contains the returned rows from the select query.
     */
    public function get($tableName, $columns = '*', $numRows = null)
    {
        if (empty ($columns))
            $columns = '*';

        $column = is_array($columns) ? implode(', ', $columns) : $columns;
        $this->_tableName = $tableName;

        $this->_query = 'SELECT ' . $column . " FROM " . $this->_tableName;
        $stmt = $this->_buildQuery($numRows);

        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $res = $this->_dynamicBindResults($stmt);
        $this->reset();

        return $res;
    }

    /**
     * Abstraction method that will compile the WHERE statement,
     * any passed update data, and the desired rows.
     * It then builds the SQL query.
     *
     * @param integer|array $numRows Array to define SQL limit in format Array ($count, $offset)
     *                               or only $count
     * @param array $tableData Should contain an array of data for updating the database.
     *
     * @return mysqli_stmt Returns the $stmt object.
     */
    protected function _buildQuery($numRows = null, $tableData = null)
    {
        // $this->_buildJoin();
        $this->_buildInsertQuery ($tableData);
        $this->_buildCondition('WHERE', $this->_where);
        // $this->_buildGroupBy();
        // $this->_buildCondition('HAVING', $this->_having);
        // $this->_buildOrderBy();
        $this->_buildLimit ($numRows);
        // $this->_buildOnDuplicate($tableData);
        // if ($this->_forUpdate)
        //     $this->_query .= ' FOR UPDATE';
        // if ($this->_lockInShareMode)
        //     $this->_query .= ' LOCK IN SHARE MODE';

        $this->_lastQuery = $this->replacePlaceHolders ($this->_query, $this->_bindParams);

        // if ($this->isSubQuery)
        //     return;

        // Prepare query
        $stmt = $this->_prepareQuery();

        // Bind parameters to statement if any
        if (count ($this->_bindParams) > 1)
            call_user_func_array(array($stmt, 'bind_param'), $this->refValues($this->_bindParams));

        return $stmt;
    }

    /**
     * Abstraction method that will build the part of the WHERE conditions
     */
    protected function _buildCondition ($operator, &$conditions) {
        if (empty ($conditions))
            return;

        //Prepare the where portion of the query
        $this->_query .= ' ' . $operator;

        foreach ($conditions as $cond) {
            list ($concat, $varName, $operator, $val) = $cond;
            $this->_query .= " " . $concat ." " . $varName;

            switch (strtolower ($operator)) {
                case 'not in':
                case 'in':
                    $comparison = ' ' . $operator. ' (';
                    if (is_object ($val)) {
                        $comparison .= $this->_buildPair ("", $val);
                    } else {
                        foreach ($val as $v) {
                            $comparison .= ' ?,';
                            $this->_bindParam ($v);
                        }
                    }
                    $this->_query .= rtrim($comparison, ',').' ) ';
                    break;
                case 'not between':
                case 'between':
                    $this->_query .= " $operator ? AND ? ";
                    $this->_bindParams ($val);
                    break;
                case 'not exists':
                case 'exists':
                    $this->_query.= $operator . $this->_buildPair ("", $val);
                    break;
                default:
                    if (is_array ($val))
                        $this->_bindParams ($val);
                    else if ($val === null)
                        $this->_query .= $operator . " NULL";
                    else if ($val != 'DBNULL' || $val == '0')
                        $this->_query .= $this->_buildPair ($operator, $val);
            }
        }
    }

        /**
     * Abstraction method that will build an INSERT or UPDATE part of the query
     */
    protected function _buildInsertQuery ($tableData) {
        if (!is_array ($tableData))
            return;

        $isInsert = preg_match ('/^[INSERT|REPLACE]/', $this->_query);
        $dataColumns = array_keys ($tableData);
        if ($isInsert)
            $this->_query .= ' (`' . implode ($dataColumns, '`, `') . '`)  VALUES (';
        else
            $this->_query .= " SET ";

        $this->_buildDataPairs ($tableData, $dataColumns, $isInsert);

        if ($isInsert)
            $this->_query .= ')';
    }

        /**
     * Abstraction method that will build the LIMIT part of the WHERE statement
     *
     * @param integer|array $numRows Array to define SQL limit in format Array ($count, $offset)
     *                               or only $count
     */
    protected function _buildLimit ($numRows) {
        if (!isset ($numRows))
            return;

        if (is_array ($numRows))
            $this->_query .= ' LIMIT ' . (int)$numRows[0] . ', ' . (int)$numRows[1];
        else
            $this->_query .= ' LIMIT ' . (int)$numRows;
    }

        /**
     * Helper function to add variables into bind parameters array
     *
     * @param string Variable value
     */
    protected function _bindParam($value) {
        $this->_bindParams[0] .= $this->_determineType ($value);
        array_push ($this->_bindParams, $value);
    }

    /**
     * Helper function to add variables into bind parameters array in bulk
     *
     * @param Array Variable with values
     */
    protected function _bindParams ($values) {
        foreach ($values as $value)
            $this->_bindParam ($value);
    }

    /**
     * Helper function to add variables into bind parameters array and will return
     * its SQL part of the query according to operator in ' $operator ?' or
     * ' $operator ($subquery) ' formats
     *
     * @param Array Variable with values
     */
    protected function _buildPair ($operator, $value) {
        if (!is_object($value)) {
            $this->_bindParam ($value);
            return ' ' . $operator. ' ? ';
        }
        $subQuery = $value->getSubQuery ();
        $this->_bindParams ($subQuery['params']);

        return " " . $operator . " (" . $subQuery['query'] . ") " . $subQuery['alias'];
    }

    /**
     * This method allows you to specify multiple (method chaining optional) AND WHERE statements for SQL queries.
     *
     * @uses $MySqliDb->where('id', 7)->where('title', 'MyTitle');
     *
     * @param string $whereProp  The name of the database field.
     * @param mixed  $whereValue The value of the database field.
     *
     * @return MysqliDb
     */
    public function where($whereProp, $whereValue = 'DBNULL', $operator = '=', $cond = 'AND')
    {
        // forkaround for an old operation api
        if (is_array ($whereValue) && ($key = key ($whereValue)) != "0") {
            $operator = $key;
            $whereValue = $whereValue[$key];
        }
        if (count ($this->_where) == 0)
            $cond = '';
        $this->_where[] = Array ($cond, $whereProp, $operator, $whereValue);
        return $this;
    }

        /**
     * Helper function to create dbObject with Json return type
     *
     * @return dbObject
     */
        public function JsonBuilder () 
        {
            $this->returnType = 'Json';
            return $this;
        }

    /**
     * Helper function to create dbObject with Array return type
     * Added for consistency as thats default output type
     *
     * @return dbObject
     */
    public function ArrayBuilder () 
    {
        $this->returnType = 'Array';
        return $this;
    }

    /**
     * Helper function to create dbObject with Object return type.
     *
     * @return dbObject
     */
    public function ObjectBuilder () 
    {
        $this->returnType = 'Object';
        return $this;
    }

    /**
     * Method attempts to prepare the SQL query
     * and throws an error if there was a problem.
     *
     * @return mysqli_stmt
     */
    protected function _prepareQuery()
    {
        if (!$stmt = $this->_connection->prepare($this->_query))
            throw new Exception ("Problem preparing query ($this->_query) " . $this->_connection->error);
        return $stmt;
    }

    /**
     * This method is needed for prepared statements. They require
     * the data type of the field to be bound with "i" s", etc.
     * This function takes the input, determines what type it is,
     * and then updates the param_type.
     *
     * @param mixed $item Input to determine the type.
     *
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
     *
     * @return string
     */
        protected function replacePlaceHolders ($str, $vals) 
        {
            $i = 1;
            $newStr = "";

            while ($pos = strpos ($str, "?")) {
                $val = $vals[$i++];
                if (is_object ($val))
                    $val = '[object]';
                if ($val === NULL)
                    $val = 'NULL';
                $newStr .= substr ($str, 0, $pos) . "'". $val . "'";
                $str = substr ($str, $pos + 1);
            }
            $newStr .= $str;
            return $newStr;
        }

 /**
 * This helper method takes care of prepared statements' "bind_result method
 * , when the number of variables to pass is unknown.
 *
 * @param mysqli_stmt $stmt Equal to the prepared statement object.
 *
 * @return array The results of the SQL fetch.
 */
 protected function _dynamicBindResults($stmt)
 {
    $parameters = array();
    $results = array();
        // See http://php.net/manual/en/mysqli-result.fetch-fields.php
    $mysqlLongType = 252;
    $shouldStoreResult = false;

    $meta = $stmt->result_metadata();

        // if $meta is false yet sqlstate is true, there's no sql error but the query is
        // most likely an update/insert/delete which doesn't produce any results
    if(!$meta && $stmt->sqlstate)
        return array();

    $row = array();
    while ($field = $meta->fetch_field()) {

        if ($field->type == $mysqlLongType)
            $shouldStoreResult = true;

        $row[$field->name] = null;
        $parameters[] = & $row[$field->name];
    }

        // avoid out of memory bug in php 5.2 and 5.3. Mysqli allocates lot of memory for long*
        // and blob* types. So to avoid out of memory issues store_result is used
        // https://github.com/joshcam/PHP-MySQLi-Database-Class/pull/119
    if ($shouldStoreResult)
       $stmt->store_result();

   call_user_func_array(array($stmt, 'bind_result'), $parameters);

   $this->totalCount = 0;
   $this->count = 0;
   while ($stmt->fetch()) {
    if ($this->returnType == 'Object') {
        $x = new \stdClass ();
        foreach ($row as $key => $val) {
            if (is_array ($val)) {
                $x->$key = new \stdClass ();
                foreach ($val as $k => $v)
                    $x->$key->$k = $v;
            } else
            $x->$key = $val;
        }
    } else {
        $x = array();
        foreach ($row as $key => $val) {
            if (is_array($val)) {
                foreach ($val as $k => $v)
                    $x[$key][$k] = $v;
            } else
            $x[$key] = $val;
        }
    }
    $this->count++;
    array_push ($results, $x);
}
if ($shouldStoreResult)
    $stmt->free_result();

$stmt->close();

return $results;
}

    /**
    * @param array $arr
    *
    * @return array
    */
    protected function refValues(Array &$arr)
    {
        //Reference in the function arguments are required for HHVM to work
        //https://github.com/facebook/hhvm/issues/5155
        //Referenced data array is required by mysqli since PHP 5.3+
        if (strnatcmp (phpversion(), '5.3') >= 0) {
            $refs = array();
            foreach ($arr as $key => $value)
                $refs[$key] = & $arr[$key];
            return $refs;
        }
        return $arr;
    }

    /**
     * Method returns mysql error
     *
     * @return string
     */
    public function getLastError () {
        if (!$this->_mysqli)
            return "mysqli is null";
        return trim ($this->_stmtError . " " . $this->_connection->error);
    }

            /**
     * Begin a transaction
     *
     * @uses mysqli->autocommit(false)
     * @uses register_shutdown_function(array($this, "_transaction_shutdown_check"))
     */
            public function startTransaction () {
                $this->_connection->autocommit (false);
                $this->_transaction_in_progress = true;
                register_shutdown_function (array ($this, "_transaction_status_check"));
            }

    /**
     * Transaction commit
     *
     * @uses mysqli->commit();
     * @uses mysqli->autocommit(true);
     */
    public function commit () {
        $this->_connection->commit ();
        $this->_transaction_in_progress = false;
        $this->_connection->autocommit (true);
    }

    /**
     * Transaction rollback function
     *
     * @uses mysqli->rollback();
     * @uses mysqli->autocommit(true);
     */
    public function rollback () {
      $this->_connection->rollback ();
      $this->_transaction_in_progress = false;
      $this->_connection->autocommit (true);
  }

      /**
     * This methods returns the ID of the last inserted item
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
     *
     * @return string The escaped string.
     */
    public function escape($str)
    {
        return $this->_connection->real_escape_string($str);
    }

        /**
     * Insert method to add new row
     *
     * @param <string $tableName The name of the table.
     * @param array $insertData Data containing information for inserting into the DB.
     *
     * @return boolean Boolean indicating whether the insert query was completed succesfully.
     */
    public function insert ($tableName, $insertData) {
        return $this->_buildInsert ($tableName, $insertData);
    }

        /**
     * Update query. Be sure to first call the "where" method.
     *
     * @param string $tableName The name of the database table to work with.
     * @param array  $tableData Array of data to update the desired row.
     *
     * @return boolean
     */
    public function update($tableName, $tableData)
    {
        if ($this->isSubQuery)
            return;

        $this->_query = "UPDATE " . self::$prefix . $tableName;

        $stmt = $this->_buildQuery (null, $tableData);
        $status = $stmt->execute();
        $this->reset();
        $this->_stmtError = $stmt->error;
        $this->count = $stmt->affected_rows;

        return $status;
    }


    /**
     * Delete query. Call the "where" method first.
     *
     * @param string  $tableName The name of the database table to work with.
     * @param integer|array $numRows Array to define SQL limit in format Array ($count, $offset)
     *                               or only $count
     *
     * @return boolean Indicates success. 0 or 1.
     */
    public function delete($tableName, $numRows = null)
    {
        if ($this->isSubQuery)
            return;

        $table = self::$prefix . $tableName;
        if (count ($this->_join))
            $this->_query = "DELETE " . preg_replace ('/.* (.*)/', '$1', $table) . " FROM " . $table;
        else
            $this->_query = "DELETE FROM " . $table;

        $stmt = $this->_buildQuery($numRows);
        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $this->reset();

        return ($stmt->affected_rows > 0);
    }

        /**
     * Internal function to build and execute INSERT/REPLACE calls
     *
     * @param <string $tableName The name of the table.
     * @param array $insertData Data containing information for inserting into the DB.
     *
     * @return boolean Boolean indicating whether the insert query was completed succesfully.
     */
    private function _buildInsert ($tableName, $insertData)
    {

        $this->_query = "INSERT " ."INTO " . $tableName;
        $stmt = $this->_buildQuery (null, $insertData);
        $stmt->execute();
        $this->_stmtError = $stmt->error;
        $this->reset();
        $this->count = $stmt->affected_rows;

        if ($stmt->affected_rows < 1)
            return false;

        if ($stmt->insert_id > 0)
            return $stmt->insert_id;

        return true;
    }

        public function _buildDataPairs ($tableData, $tableColumns, $isInsert) {
        foreach ($tableColumns as $column) {
            $value = $tableData[$column];
            if (!$isInsert)
                $this->_query .= "`" . $column . "` = ";

            // Subquery value
            if ($value instanceof MysqliDb) {
                $this->_query .= $this->_buildPair ("", $value) . ", ";
                continue;
            }

            // Simple value
            if (!is_array ($value)) {
                $this->_bindParam($value);
                $this->_query .= '?, ';
                continue;
            }

            // Function value
            $key = key ($value);
            $val = $value[$key];
            switch ($key) {
            case '[I]':
                $this->_query .= $column . $val . ", ";
                break;
            case '[F]':
                $this->_query .= $val[0] . ", ";
                if (!empty ($val[1]))
                    $this->_bindParams ($val[1]);
                break;
            case '[N]':
                if ($val == null)
                    $this->_query .= "!" . $column . ", ";
                else
                    $this->_query .= "!" . $val . ", ";
                break;
            default:
                die ("Wrong operation");
            }
        }
        $this->_query = rtrim($this->_query, ', ');
    }

}

