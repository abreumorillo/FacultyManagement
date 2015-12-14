<?php

namespace FRD\Request;

use FRD\Common\Validation;

/**
* PaperRequest class
*/
class PaperRequest
{
    /**
     * Array containing the user data formated to be processed in the database
     * @var array
     */
    protected $data = [];
    protected $isUpdate = false;

    /**
     * Array containing the array of error if any validation error.
     * @var array
     */
    protected $validationErrors = [];
    //title, abstract, citation
    function __construct($isUpdate = false)
    {
        $this->isUpdate = $isUpdate;
    }
        /**
     * Validate the incoming data request
     * @param  JSON $jsonData
     * @return mix           array of data or boolean
     */
    public function validate($jsonData)
    {
        //Title
        if (isset($jsonData->title) && !Validation::isEmpty($jsonData->title)) {
            $this->data['title'] = Validation::filterString($jsonData->title);
        } else {
            $this->validationErrors[] = 'The paper title is required';
        }

        //Abstract
        if (isset($jsonData->abstract) && !Validation::isEmpty($jsonData->abstract)) {
            $this->data['abstract'] = Validation::filterString($jsonData->abstract);
        } else {
            $this->validationErrors[] = 'The paper abstract is required';
        }

        //Citation
        if (isset($jsonData->citation) && !Validation::isEmpty($jsonData->citation)) {
            $this->data['citation'] = Validation::filterString($jsonData->citation);
        } else {
            $this->validationErrors[] = 'The citation is required';
        }

        //FacultyId
        if (isset($jsonData->facultyId) && intval($jsonData->facultyId) > 0) {
            $this->data['facultyId'] = intval($jsonData->facultyId);
        } else {
            $this->validationErrors[] = 'The faculty is required';
        }

        //Citation
        if (isset($jsonData->keywords) && !Validation::isEmpty($jsonData->keywords)) {
            $this->data['keywords'] = $jsonData->keywords;//Validation::filterString($jsonData->keywords);
        } else {
            $this->validationErrors[] = 'The keywords are required';
        }

        //Apply for update
        if ($this->isUpdate) {
            //Only make sure the id is present
            if (!isset($jsonData->id) && intval($jsonData->id) <= 0) {
                $this->validationErrors[] = 'The paper ID is required';
            }
        }

        if (empty($this->validationErrors)) {
            return $this->data;
        }
        return false;
    }

    /**
     * Get the errors if any
     * @return array
     */
    public function getErrors()
    {
        return $this->validationErrors;
    }
}
