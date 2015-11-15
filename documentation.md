#Composer packages
https://github.com/vlucas/phpdotenv

## Class public properties
$publicProperties = call_user_func('get_object_vars', $this);
var_dump($publicProperties);

## Database class public API

###getInstance ()
Return the current instance of the database. The database class follows the singleton design pattern.

###getConnection ()
Return the current connection to the database.

###getLastQuery ()
Return the last executed query.

###query ($query, $bindParams = null)
Execute a query against the database, it takes the query as well as posible parameters for binding purpose.It returns an array of object or a single object.

###noSelect ($query, $bindParams = null)
Execute INSERT, UPDATE or DELETE query against the database, it takes the query and  params for binding. It returns true or false based on the result of the operation.

###getLastError ()
Return the last mysqli statement error as well as the connection error number for debugging purpose.

###startTransaction ()
Start a database transaction.

###commit ()
Commit a database transaction.

###rollback()
Rollback a database transaction.

###getInsertId ()
Get the inserted id after an insert operation. It will return 0 if the PK is not  auto-increment.

###escape ($str)
Escapes special characters in a string for use in an SQL statement. It takes the string to be escaped as a  parameter.

###getLastInsertedId ($tableName, $primaryKeyColum = 'id')
Get the last inserted id in a given table. It takes the table name as well as the primary key column as parameter.


## DbModel class public API
This is an abstract class that is intended to be inherited by any model class. The tablename protected property should indicates the name of the table the model class represents.

###getById ($id, $fields = '*')
Get a record from the database by its ID. The method takes the id and the fields to be retrieved from the database. It returns a standard object.

###get ($condition = null, $fields = '*', $page = 1, $itemPerPage = 10)
Get the records from the database that match a given condition. The method is ready for pagination. It returns an array of objects or a single object.

###post ($data)
Save data to the database. The data parameter has to be an associative array where the key corresponds to the column in the database table. It returns the key of the inserted record.

###put ($condition, $data)
Update a record/records in the database. data and condition need to be an associative array. Returns boolean.

###delete ($condition)
Delete a record/records in the database that match the given condition. The condition parameter needs to be an associative array. It returns a boolean.

###query ($sql)
Execute a query against the database.