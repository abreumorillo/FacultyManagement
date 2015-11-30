<?php

namespace FRD\DAL\Repositories;

use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Model\Paper;

/**
* Paper repository
*/
class PaperRepository extends BaseRepository
{
    private $paper;

    public function __construct()
    {
        $this->paper = new Paper();
        parent::__construct();
    }

    public function count()
    {
        return $this->paper->count();
    }

    public function getPapers($searchTerm, $page = 1, $itemPerPage = 10)
    {
        $result =[];
        $start = ($page -1) * $itemPerPage;

        $query = "SELECT papers.id, papers.title, people.firstName ";
        $query .= "FROM papers ";
        $query .= "JOIN authorship ON papers.id = authorship.paperId ";
        $query .= "JOIN people ON authorship.facultyId = people.id ";
        $query .= "WHERE papers.title like ? OR people.firstName like ?  ";
        $query .= "LIMIT ? OFFSET ?";
        $searchTerm = "%".$searchTerm."%";
        $params = array($searchTerm, $searchTerm, $itemPerPage, $start);

        $papers = $this->db->query($query, $params);
        $keywordQuery = "SELECT id, keyword FROM paper_keywords WHERE id = ?";

        //get the keywords
        if (count($papers)> 1) {
            foreach ($papers as $paper) {
                $paper->keywords = $this->db->query($keywordQuery, array($paper->id));
                $result[]= $paper;
            }
            return $result;
        } else {
            $papers->keywords = $this->db->query($keywordQuery, array($papers->id));
            return $papers;
        }
    }

    public function getAll($fields = '*')
    {
        return $this->paper->getAll($fields);
    }

    public function getById($id, $fields = '*')
    {
        return  $this->paper->getById($id, $fields);
    }
}
