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

        //action, itemPerPage, page, searchTerm
        //echo json_encode($paperRepository->getAll(['title']));
        // echo json_encode($paperRepository->getPapers('steve'));
        break;
    case 'POST':
        echo 'POST';
        break;
    case 'PUT':
        echo 'PUT';
        break;
    case 'DELETE':
        echo 'DELETE';
        break;
}