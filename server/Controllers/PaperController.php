<?php
require_once('../../vendor/autoload.php');

use FRD\Model\Paper;
use FRD\Common\CommonFunction;
use FRD\Common\Validation;

$requestMethod = CommonFunction::getRequestMethod();

var_dump($requestMethod);