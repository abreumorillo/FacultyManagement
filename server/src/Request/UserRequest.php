<?php

namespace FRD\Request;

use FRD\Common\Validation;

/**
 * UserRequest.
 */
abstract class UserRequest
{
    /**
     * Array containing the user data formated to be processed in the database
     * @var array
     */
    protected $userData = [];
    protected $isInsert = false;

    /**
     * Array containing the array of error if any validation error.
     * @var array
     */
    protected $validationErrors = [];


    /**
     * Validate the incoming data request
     * @param  JSON $jsonData
     * @return mix           array of data or boolean
     */
    public function validate($jsonData)
    {
        //Last name
        if (isset($jsonData->lastName) && !Validation::isEmpty($jsonData->lastName)) {
            $this->userData['lastName'] = Validation::filterString($jsonData->lastName);
        } else {
            $this->validationErrors[] = 'The Last name is required';
        }

        //First name
        if (isset($jsonData->firstName) && !Validation::isEmpty($jsonData->firstName)) {
            $this->userData['firstName'] = Validation::filterString($jsonData->firstName);
        } else {
            $this->validationErrors[] = 'The First name is required';
        }

        //User name
        if (isset($jsonData->username) && !Validation::isEmpty($jsonData->username)) {
            $this->userData['username'] = Validation::filterString($jsonData->username);
        } else {
            $this->validationErrors[] = 'The username is required';
        }
        if ($this->isInsert || isset($jsonData->password)) {
            //password
            if (isset($jsonData->password) && !Validation::isEmpty($jsonData->password)) {
                $this->userData['password'] = hash('md5', Validation::filterString($jsonData->password));
            } else {
                $this->validationErrors[] = 'The password is required';
            }

        //Confirm password
            if (isset($jsonData->confirmPassword) && !Validation::isEmpty($jsonData->confirmPassword)) {
                if ($jsonData->confirmPassword !== $jsonData->password) {
                    $this->validationErrors[] = 'Password and confirm password must match';
                }
            }
        }

        //Email
        if (isset($jsonData->email) && Validation::validateEmail($jsonData->email) !== false) {
            $this->userData['email'] = Validation::filterString($jsonData->email);
        } else {
            $this->validationErrors[] = 'Invalid email provided';
        }

        //RoleId
        if (isset($jsonData->roleId) && intval($jsonData->roleId) > 0) {
            $this->userData['roleId'] = intval($jsonData->roleId);
        } else {
            $this->validationErrors[] = 'The role is required';
        }

        if (empty($this->validationErrors)) {
            return $this->userData;
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

    /*
    $data = [
'lastName' => '',
'firstName' => '',
'password' =>  '',
'email' => '',
roleId =>''
];
     */
