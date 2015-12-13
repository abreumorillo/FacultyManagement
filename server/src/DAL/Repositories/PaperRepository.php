<?php

namespace FRD\DAL\Repositories;

use FRD\Common\Response;
use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Model\Paper;

/**
 * Paper repository.
 */
class PaperRepository extends BaseRepository
{
    private $paper;

    public function __construct()
    {
        $this->paper = new Paper();
        parent::__construct();
    }

    public function count($searchTerm = null)
    {
        if (!$searchTerm || empty($searchTerm)) {
            return intval($this->paper->count());
        }else {
            $query  = "SELECT COUNT(*) as count ";
            $query .= 'FROM papers ';
            $query .= 'JOIN authorship ON papers.id = authorship.paperId ';
            $query .= 'JOIN people ON authorship.facultyId = people.id ';
            $query .= 'WHERE papers.title like ? OR people.firstName like ?  OR people.lastName like ? ';

            $searchTerm = '%'.$searchTerm.'%';
            $params = array($searchTerm, $searchTerm, $searchTerm);
            $result = $this->db->query($query, $params);
            if($result) {
                return  $result->count;
            }
            return 0;
        }
    }

    public function getPapers($searchTerm, $page = 1, $itemPerPage = 10)
    {
        $result = [];
        $start = ($page - 1) * $itemPerPage;

        $query = 'SELECT papers.id, papers.title, CONCAT_WS(" ", people.lastName, people.firstName) AS author ';
        $query .= 'FROM papers ';
        $query .= 'JOIN authorship ON papers.id = authorship.paperId ';
        $query .= 'JOIN people ON authorship.facultyId = people.id ';
        $query .= 'WHERE papers.title like ? OR people.firstName like ?  OR people.lastName like ? ';
        $query .= 'LIMIT ? OFFSET ?';
        $searchTerm = '%'.$searchTerm.'%';
        $params = array($searchTerm, $searchTerm, $searchTerm, $itemPerPage, $start);

        $papers = $this->db->query($query, $params);
        $keywordQuery = 'SELECT id, keyword FROM paper_keywords WHERE id = ?';

        //get the keywords
        if (is_array($papers) && count($papers) >= 1) {
            foreach ($papers as $paper) {
                $paper->keywords = $this->db->query($keywordQuery, array($paper->id));
                $result[] = $paper;
            }

            return $result;
        } elseif (is_object($papers)) {
            $papers->keywords = $this->db->query($keywordQuery, array($papers->id));

            return $papers;
        } else {
            return Response::notFound();
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
