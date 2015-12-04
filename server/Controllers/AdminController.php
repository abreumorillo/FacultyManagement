<?php

require_once '../../bootstrapper.inc';

use FRD\Common\CommonFunction;
use FRD\DAL\Repositories\AdminRepository;

$requestMethod = CommonFunction::getRequestMethod();
$adminRepository = new AdminRepository();

switch ($requestMethod) {
    case 'GET':
        switch (CommonFunction::getRequestAction()) {
            case 'getUsers':
                echo json_encode($adminRepository->getUsers());
                break;
            case 'getRoles':
                echo json_encode($adminRepository->getRoles());
                break;
        }
        break;
    case 'POST':
        $jsonUserData = json_decode($_POST['data']);
        echo json_encode($adminRepository->saveUser($jsonUserData));
        break;
    case 'PUT':
        echo 'PUT';
        break;
    case 'DELETE':
        echo 'DELETE';
        break;
}