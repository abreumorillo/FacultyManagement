<?php

namespace FRD\Request;

use FRD\Common\Validation;

/**
* KeywordRequest
*/
class LoginRequest
{
    /**
     * Array containing the user data formated to be processed in the database
     * @var array
     */
    protected $data = [];
    /**
     * Array containing the array of error if any validation error.
     * @var array
     */
    protected $validationErrors = [];

    public function validate($jsonData)
    {
        if (isset($jsonData->username) && !Validation::isEmpty($jsonData->username)) {
            $this->data['username'] = Validation::filterString($jsonData->username);
        } else {
            $this->validationErrors[] = 'The username is required';
        }
        if (isset($jsonData->password) && !Validation::isEmpty($jsonData->password)) {
            $this->data['password'] = hash('md5', Validation::filterString($jsonData->password));
        } else {
            $this->validationErrors[] = 'The password is required';
        }

        if(empty($this->validationErrors)) {
            return $this->data;
        }
        return false;
    }

     /**
     * Get the validation errors
     * @return [array]
     */
     public function getErrors()
     {
        return $this->validationErrors;
    }
}
