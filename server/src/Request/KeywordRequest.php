<?php

namespace FRD\Request;

use FRD\Common\Validation;

/**
* KeywordRequest
*/
class KeywordRequest
{

    /**
     * Array containing the user data formated to be processed in the database
     * @var array
     */
    protected $data = [];
    protected $isUpdate = false;

    public function __construct($isUpdate = false)
    {
        $this->isUpdate = $isUpdate;
    }

    /**
     * Array containing the array of error if any validation error.
     * @var array
     */
    protected $validationErrors = [];

    public function validate($jsonData)
    {
        if (isset($jsonData->description) && !Validation::isEmpty($jsonData->description)) {
            $this->data['description'] = Validation::filterString($jsonData->description);
        } else {
            $this->validationErrors[] = 'The description of the keyword is required';
        }
        if ($this->isUpdate) {
            //Only make sure the id is present
            if (!isset($jsonData->id) && intval($jsonData->id) <= 0) {
                $this->validationErrors[] = 'The ID is required';
            }
        }
        if(empty($this->validationErrors)) {
            return $this->data;
        }
        return false;
    }

     /**
     * Get the validation errors
     * @return [type] [description]
     */
     public function getErrors()
     {
        return $this->validationErrors;
    }
}
