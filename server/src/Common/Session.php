<?php

namespace FRD\Common;

/**
* Session class
*/
class Session
{
    public static function setUserSession($user)
    {
        $_SESSION['userId'] = $user->id;
        $_SESSION['userRole'] = $user->role;
    }
}
