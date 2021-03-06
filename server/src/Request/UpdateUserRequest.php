<?php

namespace FRD\Request;

use FRD\Request\UserRequest;

/**
 * UpdateUserRequest class
 * The purpose of this class is to provide validation logic for the user update process.
 */
class UpdateUserRequest extends UserRequest
{
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

    public function getErrors()
    {
        return $this->validationErrors;
    }
}
