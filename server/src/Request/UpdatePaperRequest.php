<?php

namespace FRD\Request;

use FRD\Request\PaperRequest;

/**
* UpdatePaperRequest class
*/
class UpdatePaperRequest extends PaperRequest
{
    protected  $isUpdate = true;
    public function validate($jsonData)
    {
        //Only make sure the id is present
        if (!isset($jsonData->id) && intval($jsonData->id) <= 0) {
            $this->validationErrors[] = 'The user ID is required';
        }

        parent::validate($jsonData);

        if (empty($this->validationErrors)) {
            return $this->userData;
        }

        return false;
    }
}
