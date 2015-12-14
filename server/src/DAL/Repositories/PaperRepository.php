<?php

namespace FRD\DAL\Repositories;

use FRD\Common\CommonFunction;
use FRD\Common\Response;
use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Model\Paper;
use FRD\Request\PaperRequest;

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

    /**
     * Get the papers that match a certain condition
     * @param  string  $searchTerm  []
     * @param  integer $page        []
     * @param  integer $itemPerPage []
     * @return [mix]               []
     */
    public function getPapers($searchTerm, $page = 1, $itemPerPage = 10)
    {

        $result = [];
        $start = ($page - 1) * $itemPerPage;

        $query = 'SELECT papers.id, papers.title, CONCAT_WS(" ", people.lastName, people.firstName) AS author ';
        $query .= 'FROM papers ';
        $query .= 'JOIN authorship ON papers.id = authorship.paperId ';
        $query .= 'JOIN people ON authorship.facultyId = people.id ';
        if($searchTerm !== "*") {
            $query .= 'WHERE papers.title like ? OR people.firstName like ?  OR people.lastName like ? ';
        }
        $query .= 'LIMIT ? OFFSET ?';
        if($searchTerm == "*") {
            $allParams = array($itemPerPage, $start);
            $papers = $this->db->query($query, $allParams);
        }else {
            $searchTerm = '%'.$searchTerm.'%';
            $params = array($searchTerm, $searchTerm, $searchTerm, $itemPerPage, $start);
            $papers = $this->db->query($query, $params);
        }
        //Select keywords related to a paper
        $keywordQuery = "SELECT id, description ";
        $keywordQuery .= "FROM keywords ";
        $keywordQuery .= "JOIN papers_keywords ON keywords.id = papers_keywords.keywordId ";
        $keywordQuery .= "WHERE papers_keywords.paperId = ?";

        //get the keywords
        if (is_array($papers) && count($papers) >= 1) {
            foreach ($papers as $paper) {
                $paper->keywords = $this->getKeywords( $this->db->query($keywordQuery, array($paper->id)));
                $result[] = $paper;
            }
            return $result;
        } elseif (is_object($papers)) {
            $papers->keywords = $this->getKeywords($this->db->query($keywordQuery, array($papers->id)));
            return $papers;
        } else {
            return Response::notFound();
        }
    }

    /**
     * Get all papers
     * @param  string $fields
     * @return array
     */
    public function getAll($fields = '*')
    {
        return $this->paper->getAll($fields);
    }

    /**
     * Get a paper by id
     * @param  int $id
     * @param  array $fields
     * @return object
     */
    public function getById($id)
    {
        $paper =  $this->paper->getById($id);
        if (CommonFunction::isValidResponse($paper)) {
            return $paper;
        }
        return Response::notFound();
    }


    /**
     * Insert a paper
     * @param  JSON $jsonData
     * @return Response
     */
    public function insert($jsonData)
    {
        $request = new PaperRequest();
        $data = $request->validate($jsonData);
        if (!$data) {
            return Response::validationError($request->getErrors());
        } else {
            //return $data;
            $response = $this->paper->post($data);
            if ($response) {
                return Response::created($response);
            }
            return Response::serverError($response, $this->db->getLastError());
        }
    }

    /**
     * Format keywords as an array
     */
    private function  getKeywords($keywords)
    {
        if(is_array($keywords)){
            return $keywords;
        }
        $result[] = $keywords;
        return  $result;
    }
}
