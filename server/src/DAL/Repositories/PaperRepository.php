<?php

namespace FRD\DAL\Repositories;

use FRD\Model\Paper;


/**
* Paper repository
*/
class PaperRepository
{

    private $paper;

    function __construct()
    {
        $this->paper = new Paper();
    }

    public function count()
    {
        return $this->paper->count();
    }

    public function getAll($fields)
    {
        return $this->paper->getAll($fields);
    }

    public function getById($id, $fields = '*')
    {
        return  $this->paper->getById($id, $fields);
    }

}