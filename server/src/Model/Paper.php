<?php

namespace FRD\Model;

use FRD\Model\base\DbModel;

/**
* Represent the paper table in the database
*/
class Paper extends DbModel
{
    /**
     * Table name
     * @var string
     */
    protected $tableName = 'papers';

    /**
     * To indicate wheather or not this table uses auto increment PK
     * @var boolean
     */
    protected $isAutoIncrement = false;

}
