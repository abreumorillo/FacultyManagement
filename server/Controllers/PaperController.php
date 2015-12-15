<?php
require_once('../../session.inc');
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

        break;
    case 'POST':
        $jsonData = json_decode($_POST['data']);
        //echo json_encode($jsonData);
        switch ($jsonData->action) {
            case 'insert':
                echo json_encode($paperRepository->insert($jsonData));
                break;
            case 'update':
                echo json_encode($paperRepository->update($jsonData));
                break;
        }
        break;

    case 'DELETE':
        $paperId = CommonFunction::getValue('paperId');
        echo json_encode($paperRepository->delete($paperId));
        break;
}
