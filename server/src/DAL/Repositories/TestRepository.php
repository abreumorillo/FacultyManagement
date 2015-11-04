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

    public function test($input)
    {
        echo $input;
    }

    public function testQuery()
    {
        $query = "SELECT * FROM papers";
        $result = $this->mysqli->query($query);
        if($result->num_rows > 0) {
            while($row = $result->fetch_object()){
                var_dump($row);
            }
        }

    }

}