<?php
require_once('../../session.inc');
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
            case 'getUserById':
                $userId = CommonFunction::getValue('userId');
                echo json_encode($adminRepository->getUserById($userId));
                break;
            case 'getRoles':
                echo json_encode($adminRepository->getRoles());
                break;
            case 'getFacultiesList':
                echo json_encode($adminRepository->getFacultiesList());
        }
        break;
    case 'POST':
        $jsonUserData = json_decode($_POST['data']);
        switch ($jsonUserData->action) {
            case 'insert':
                echo json_encode($adminRepository->saveUser($jsonUserData));
                break;
            case 'update':
                echo json_encode($adminRepository->updateUser($jsonUserData));
                break;
        }
        break;
    case 'DELETE':
        $userId = CommonFunction::getValue('userId');
        echo json_encode($adminRepository->deleteUser($userId));
        break;
}
