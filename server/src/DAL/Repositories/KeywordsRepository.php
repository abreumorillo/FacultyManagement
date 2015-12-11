<?php

namespace FRD\DAL\Repositories;

use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Model\Keyword;

/**
* Keywords repository
*/
class KeywordsRepository extends BaseRepository
{
    private $keyword;

    public function __construct()
    {
        parent::__construct();
        $this->keyword = new Keyword();
    }

    public function getAll()
    {
        return $this->keyword->getAll();
    }

    public function getById($id)
    {
        return $this->keyword->getById($id);
    }

    /**
     * Delete a keyworkd form the database
     * @param  int $keywordId
     * @return mix
     */
    public function delete($keywordId)
    {
        if ($this->keyword->delete(['id'=> $keywordId])) {
            return Response::noContent();
        }
        return Response::serverError([], $this->db->getLastError());
    }
}
