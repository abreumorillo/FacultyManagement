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

    public static  function getUserId()
    {
        if(isset($_SESSION['userId'])) {
            return  $_SESSION['userId'];
        }
    }

    public static  function getUserRole()
    {
        if(isset($_SESSION['userRole'])) {
            return  $_SESSION['userRole'];
        }
    }

    public static  function isManagerLoggedin()
    {
        if(isset($_SESSION['userRole'])){
            return ($_SESSION['userRole'] === 'Faculty' || $_SESSION['userRole'] === 'Admin');
        }
    }
}
