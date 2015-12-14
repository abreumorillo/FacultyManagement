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

    public function post($data)
    {
        $this->db->startTransaction();
        $insertedPaperId = 0;
        $isInsertedKeywords = false;
        $facultyId = $data['facultyId'];
        $keywords = $data['keywords'];
        unset($data['facultyId']);
        unset($data['keywords']);
        //Insert paper
        $insertedPaperId = parent::post($data);
        if ($insertedPaperId > 0) {
            $authorshipQuery = "INSERT INTO authorship (facultyId, paperId) VALUES (?, ?)";
            $authorshipParams = array($facultyId, $insertedPaperId);
            $isInsertedAuthorship = $this->db->noSelect($authorshipQuery, $authorshipParams);
            //keywords
            $keywordQuery = "INSERT INTO papers_keywords (paperId, keywordId) VALUES (?, ?)";
            foreach ($keywords as $keyword) {
                $keywordParam = array($insertedPaperId, $keyword);
                $isInsertedKeywords = $this->db->noSelect($keywordQuery, $keywordParam);
            }
            if ($isInsertedAuthorship && $isInsertedKeywords) {
                $this->db->commit();
                return $insertedPaperId;
            } else {
                $this->db->rollback();
                return 0;
            }
        } else {
            $this->db->rollback();
            return 0;
        }
        return 0;
    }

    /**
     * Get keyword for a paper
     * @param  integer $paperId
     * @return mix
     */
    public function getKeywordsByPaperId($paperId)
    {
        //Select keywords related to a paper
        $query = "SELECT id, description ";
        $query .= "FROM keywords ";
        $query .= "JOIN papers_keywords ON keywords.id = papers_keywords.keywordId ";
        $query .= "WHERE papers_keywords.paperId = ?";
        return $this->processKeywords($this->db->query($keywordQuery, array($paperId)));
    }

    /**
     * Format keywords as an array
     */
    private function processKeywords($keywords)
    {
        if (is_array($keywords)) {
            return $keywords;
        }
        $result[] = $keywords;
        return  $result;
    }

    /**
     * Get a paper by ID
     * @param  [type] $paperId [description]
     * @return [type]          [description]
     */
    public function getById($paperId, $fields='*')
    {
        $query = 'SELECT papers.id, papers.title, papers.abstract, papers.citation ';
        $query .= 'FROM papers ';
        $query .= 'WHERE papers.id = ?';
        $params = array($paperId);
        $paper = $this->db->query($query, $params);

        if ($paper) {
            $paper->facultyId = $this->getFacultyIdByPaperId($paperId);
            $paper->keywords = $this->getKeywordIdsByPaperId($paperId);
        }
        return  $paper;
    }

    /**
     * Get the faculty id by paper id
     * @param  Integer $paperId
     * @return integer
     */
    public function getFacultyIdByPaperId($paperId)
    {
        $query = "SELECT facultyId FROM authorship WHERE paperId = ?";
        $param = array($paperId);
        $faculty = $this->db->query($query, $param);
        if ($faculty) {
            return $faculty->facultyId;
        }
        return 0;
    }

    /**
     * The the keyword id of a given paper id
     * @param  integer $paperId
     * @return array
     */
    public function getKeywordIdsByPaperId($paperId)
    {
        $query = "SELECT keywordId FROM papers_keywords WHERE paperId = ?";
        $param = array($paperId);
        $keywords = $this->db->query($query, $param);
        if ($keywords) {
            return $this->processKeywordsIds($keywords);
        }
        return [];
    }

    /**
     * process the keywords ids
     * @param  mix $keywords
     * @return array
     */
    private function processKeywordsIds($keywords)
    {
        $keywordsIds = [];
        if (is_array($keywords)) {
            foreach ($keywords as $keyword) {
                $keywordsIds[] = $keyword->keywordId;
            }
        } else {
            $keywordsIds[] = $keywords->keywordId;
        }
        return $keywordsIds;
    }
}

