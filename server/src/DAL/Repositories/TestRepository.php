<?php

namespace FRD\DAL\Repositories;

use FRD\DAL\Repositories\base\BaseRepository;


/**
* Test Repository
*/
class TestRepository extends BaseRepository
{

    function __construct()
    {
        parent::__construct();
    }

    // public function testQuery()
    // {
    //     $query = "SELECT * FROM papers";
    //     $result = $this->mysqli->query($query);

    //     var_dump($result);
    // }

}