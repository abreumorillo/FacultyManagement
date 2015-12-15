<?php
require_once('../../session.inc');
require_once('../../bootstrapper.inc');

use FRD\Common\CommonFunction;
use FRD\DAL\Repositories\LoginRepository;

$requestMethod = CommonFunction::getRequestMethod();
$loginRepository = new LoginRepository();

switch ($requestMethod) {
    case 'POST':
    $jsonData = json_decode($_POST['data']);
    switch ($jsonData->action) {
        case 'login':
        echo json_encode($loginRepository->login($jsonData));
        break;
        case 'logout':
            //verify if session exist
        if(isset($_SESSION['userId'])){
            //echo session_name();
                //unset all the session variables
            session_unset();
                //verify if cookie associated with the session exits
            if(isset($_COOKIE[session_name()])){
                //unset the cookie
                setcookie(session_name(), "", 1, "/");
            }
                //Destroy the session
            session_destroy();
            $successResponse = array(
                'error'=> '',
                'message' => 'logout success',
                'isAuthenticated'=>false
                );
            echo json_encode($successResponse);
            break;
        }
        break;
    }
}
/*
if(isset($_SESSION['userId'])){
                echo session_name();
                //unset all the session variables
                session_unset();
                echo session_name();
                //verify if cookie associated with the session exits
                if(isset($_COOKIE[session_name()])){
                    //unset the cookie
                    setcookie(session_name(), "", 1, "/");
                }
                //Destroy the session
                session_destroy();
                $successResponse = array(
                    'error'=> '',
                    'message' => 'logout success',
                    'isAuthenticated'=>false
                );
                echo json_encode($successResponse);
            }
 */