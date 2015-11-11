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

    public function testQuery($id)
    {

        $query = "SELECT * FROM papers WHERE id = 1";
        // $id = $this->db->escape(5);
        // var_dump($id);
        $papers = $this->db->query($query);

        var_dump($papers);

    }

    public function getLastInsertedId() {
        return $this->db->getLastInsertedId('papers');
    }

    public function insertData()
    {
        //id, title, abstract, citation,
        $nextPaperId = ($this->db->getLastInsertedId('papers') + 1);
        $query = "INSERT INTO papers (id, title, abstract, citation) VALUES (?, ?, ?, ?)";
        //
        // $query = "INSERT INTO papers SET
        // id = ?,
        // title = ?,
        // abstract = ?,
        // citation = ? ";
        $id = $nextPaperId;
        $title = "Paper title $id";
        $abstract = "Paper abstract $id";
        $citation = "Citation $id";

        return $this->db->noSelect($query, array($nextPaperId, $title, $abstract, $citation));

    }

}