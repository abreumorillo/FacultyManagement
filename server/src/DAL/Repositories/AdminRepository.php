<?php

namespace FRD\DAL\Repositories;

use FRD\Common\Response;
use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Model\People;
use FRD\Request\AddUserRequest;

/**
 * Admin Repository.
 */
class AdminRepository extends BaseRepository
{
    private $people;
    public function __construct()
    {
        parent::__construct();
        $this->people = new People();
    }

    /**
     * Get all the users from the database.
     *
     * @return [type] [description]
     */
    public function getUsers()
    {
        $query = 'SELECT people.id, people.firstName, people.lastName, people.email, roles.description AS role ';
        $query .= 'FROM people ';
        $query .= 'JOIN roles ON people.roleId = roles.id';
        $users = $this->db->query($query);
        if ($this->isValidResponse($users)) {
            return $users;
        }

        return Response::notFound();
    }

    /**
     * Get all the available roles from the database.
     *
     * @return [type] [description]
     */
    public function getRoles()
    {
        $query = 'SELECT id, description FROM roles';
        $roles = $this->db->query($query);
        if ($this->isValidResponse($roles)) {
            return $roles;
        }

        return Response::notFound();
    }

    public function saveUser($jsonUser)
    {
        $userRequest = new AddUserRequest();
        $userData = $userRequest->validate($jsonUser);
        if (!$userData) {
            return Response::validationError($userRequest->getErrors());
        } else {
            $response = $this->people->post($userData);
            if ($response) {
                return Response::created($response);
            }
            return Response::serverError($response, $this->db->getLastError());
        }
    }

    /**
     * Verify is the result from a query is wheather an object or an array, if it is the case
     * then the response would be valid data otherwise not found.
     *
     * @param mix $input input data
     *
     * @return bool
     */
    private function isValidResponse($input)
    {
        return (is_array($input) && count($input) > 0) || is_object($input);
    }
}
