<?php

namespace FRD\Request;

use FRD\Request\UserRequest;

/**
 * AddUserRequest class
 * The purpose of this class is to provide validation logic for the user creation process.
 */
class AddUserRequest extends UserRequest
{

    public function validate($jsonData)
    {
        parent::validate($jsonData);

        if(empty($this->validationErrors)) {
            return $this->userData;
        }
        return false;
    }

    public function getErrors()
    {
        return $this->validationErrors;
    }


}
