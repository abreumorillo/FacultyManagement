<?php

require_once('../../bootstrapper.inc');
function exception_handler($exception)
{
    http_response_code(500);
    echo  "Uncaught exception: " , $exception->getMessage(), "\n";
}

set_exception_handler('exception_handler');

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
                $searchTerm = CommonFunction::getValue('searchTerm');
                $page = CommonFunction::getValue('page');
                $itemPerPage = CommonFunction::getValue('itemPerPage');
                echo json_encode($paperRepository->getPapers($searchTerm, $page, $itemPerPage));
                break;
            case 'count':
                $searchTerm = CommonFunction::getValue('searchTerm');
                echo json_encode($paperRepository->count($searchTerm));
                break;
            case 'getById':
                $paperId = CommonFunction::getValue('paperId');
                echo json_encode($paperRepository->getById($paperId));
                break;
        }

        //action, itemPerPage, page, searchTerm
        //echo json_encode($paperRepository->getAll(['title']));
        // echo json_encode($paperRepository->getPapers('steve'));
        break;
    case 'POST':
        $jsonData = json_decode($_POST['data']);
        switch ($jsonData->action) {
            case 'insert':
                // echo json_encode($jsonData);
                echo json_encode($paperRepository->insert($jsonData));
                break;
            case 'update':
                echo json_encode($paperRepository->update($jsonData));
                break;
        }
        break;
    case 'PUT':
        echo 'PUT';
        break;
    case 'DELETE':
        echo 'DELETE';
        break;
}
