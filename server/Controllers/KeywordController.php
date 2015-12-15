<?php
require_once('../../session.inc');
require_once('../../bootstrapper.inc');

use FRD\Common\CommonFunction;
use FRD\DAL\Repositories\KeywordRepository;

$keywordRepository = new KeywordRepository();
$requestMethod = CommonFunction::getRequestMethod();
switch ($requestMethod) {
    case 'GET':
        switch (CommonFunction::getRequestAction()) {
            case 'getAll':
                // echo json_encode($_GET);
                echo json_encode($keywordRepository->getAll());
                break;
            case 'getById':
                $keywordId = CommonFunction::getValue('keywordId');
                echo json_encode($keywordRepository->getById($keywordId));
                break;
        }
        break;
    case 'POST':
        $jsonData = json_decode($_POST['data']);
        switch ($jsonData->action) {
            case 'insert':
                echo json_encode($keywordRepository->insert($jsonData));
                break;
            case 'update':
                echo json_encode($keywordRepository->update($jsonData));
                break;
        }
        break;

    case 'DELETE':
        $keywordId = CommonFunction::getValue('keywordId');
        echo json_encode($keywordRepository->delete($keywordId));
        break;
}
