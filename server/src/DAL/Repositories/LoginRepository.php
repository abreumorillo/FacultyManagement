<?php

namespace FRD\DAL\Repositories;

use FRD\Common\Response;
use FRD\DAL\Repositories\base\BaseRepository;
use FRD\Request\LoginRequest;
use FRD\Common\Session;

/**
* LoginRepository
*/
class LoginRepository extends BaseRepository
{
    private $paper;

    public function __construct()
    {
        parent::__construct();
    }

    public function login($jsonData)
    {
        $request = new LoginRequest();
        $credentials = $request->validate($jsonData);
        if(!$credentials) {
            return Response::validationError($request->getErrors());
        }
        $query = "SELECT people.id, people.username, roles.description AS role ";
        $query .= "FROM people ";
        $query .= "JOIN roles ON people.roleId = roles.id ";
        $query .= "WHERE people.username = ? AND people.password = ? LIMIT 1";
        $params = array($credentials['username'], $credentials['password']);
        $user = $this->db->query($query, $params);
        if ($user) {
            Session::setUserSession($user);
            $user->isAuthenticated = true;
            unset($user->id);
            return $user;
        }
        return Response::serverError($user, $this->db->getLastError());
    }
}
