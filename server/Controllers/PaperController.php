<?php

require_once('../../bootstrapper.inc');

use FRD\Common\CommonFunction;
use FRD\Common\Validation;
use FRD\DAL\Repositories\PaperRepository;
use FRD\Model\Paper;

$requestMethod = CommonFunction::getRequestMethod();
$paperRepository = new PaperRepository();

switch ($requestMethod) {
    case 'GET':
        switch (CommonFunction::getRequestAction()) {
            case 'getPapers':
                // echo json_encode($_GET);
                $searchTerm = CommonFunction::getValue('searchTerm');
                echo json_encode($paperRepository->getPapers($searchTerm));
                break;
            case 'count':
                $searchTerm = CommonFunction::getValue('searchTerm');
                echo json_encode($paperRepository->count($searchTerm));
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