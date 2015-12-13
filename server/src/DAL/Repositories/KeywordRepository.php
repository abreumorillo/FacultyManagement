<?php

namespace FRD\DAL\Repositories;

use FRD\Common\CommonFunction;
use FRD\Common\Response;
use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Model\Keyword;
use FRD\Request\KeywordRequest;

/**
* Keywords repository
*/
class KeywordRepository extends BaseRepository
{
    private $keyword;

    public function __construct()
    {
        parent::__construct();
        $this->keyword = new Keyword();
    }

    /**
     * Get all the keyword from the database
     * @return array
     */
    public function getAll()
    {
        $keywords =  $this->keyword->getAll();
        if (CommonFunction::isValidResponse($keywords)) {
            return $keywords;
        }
        return Response::noContent();
    }

    /**
     * Get the keyword by Id
     * @param  int $id
     * @return object
     */
    public function getById($id)
    {
        $keyword =  $this->keyword->getById($id);
        if (CommonFunction::isValidResponse($keyword)) {
            return $keyword;
        }
        return Response::notFound();
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

    /**
     * Insert a keyword to the database
     * @param  JSON $jsonData
     * @return Response
     */
    public function insert($jsonData)
    {
        $request = new KeywordRequest();
        $data = $request->validate($jsonData);
        if (!$data) {
            return Response::validationError($request->getErrors());
        } else {
            $response = $this->keyword->post($data);
            if ($response) {
                return Response::created($response);
            }
            return Response::serverError($response, $this->db->getLastError());
        }
    }

    /**
     * Update a keyword in the database
     * @param  JSON $jsonData
     * @return Response
     */
    public function update($jsonData)
    {
        $request = new KeywordRequest(true);
        $data = $request->validate($jsonData);
        if (!$data) {
            return Response::validationError($request->getErrors());
        } else {
            $response = $this->keyword->put(['id'=> $jsonData->id], $data);
            if ($response) {
                return Response::noContent();
            }
            return Response::serverError($response, $this->db->getLastError());
        }
    }
}
