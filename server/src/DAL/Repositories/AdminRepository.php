<?php

namespace FRD\DAL\Repositories;

use FRD\Common\Response;
use FRD\DAL\Repositories\base\BaseRepository;

/**
 * Admin Repository.
 */
class AdminRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all the users from the database.
     *
     * @return [type] [description]
     */
    public function getUsers()
    {
        $query = 'SELECT people.firstName, people.lastName, people.email, roles.description ';
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
