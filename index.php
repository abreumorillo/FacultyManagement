<?php

require_once('bootstrapper.inc');


use FRD\Common\CommonFunction;
use FRD\DAL\Repositories\PaperRepository;
use FRD\DAL\Repositories\TestRepository;
use FRD\Model\Paper;

//$repo = new TestRepository();

// $id = $repo->getLastInsertedId() + 1;
// var_dump($id);
// var_dump($repo->updateData(15))
 // $repo->testQuery(7);
// var_dump($repo->insertData());
// var_dump($repo->deleteData(16));
//
// $arrayAso = ['val1'=>'value1', 'val2' => 'value 2', 'val3'=>'value 3'];
// $array = array('test1', 'test2', 'test3');
// echo "'".implode(",", array_keys($arrayAso))."'";
// echo '<br>';
// echo implode(" ", $array);

// var_dump(CommonFunction::isAssociativeArray($arrayAso));
//$paper = new Paper();
$paperRepository = new PaperRepository();

//var_dump($paper->getById(10));
// var_dump($paper->get(['title' => 'Updated title'], ['title', 'abstract'], 1,20));
$data = [
'title' => 'Paper Model title updated',
'abstract' => 'Paper Model abstract updated',
'citation' =>  'citation1 citation2, citation 3, citation 4'
];
//$last_key = end(array_keys($data));
// var_dump($paper->post($data));


//$paper->put(array('id' => 20),$data);
//var_dump($paper->delete(array('id' => 19 )));
//var_dump($paper->count());
// var_dump($paperRepository->getAll(['title', 'abstract']));

// var_dump($_SERVER);

?>

<!DOCTYPE html>
<html lang="en" ng-app="frdApp">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
  <title>Faculty Research Database</title>

  <!-- Bootstrap -->
  <link href='https://fonts.googleapis.com/css?family=Open+Sans|Lora|Vollkorn|Josefin+Slab' rel='stylesheet' type='text/css'>
  <link href="assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/font-awesome.min.css" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/angular-toastr.min.css">
  <link rel="stylesheet" href="assets/css/nga.min.css">
  <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
        page. However, you can choose any other skin. Make sure you
        apply the skin class to the body tag so the changes take effect.
  -->
  <link rel="stylesheet" href="assets/css/chosen.css">
  <link rel="stylesheet" href="assets/css/skins/skin-blue.min.css">
  <link href="assets/css/style.css" rel="stylesheet">

  <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

  </head>

  <body>
    <header>
      <nav class="navbar navbar-blue navbar-fixed-top">
        <div class="container">
          <!-- Brand and toggle get grouped for better mobile display -->
          <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" ui-sref="index">Faculty Research Database</a>
          </div>

          <!-- Collect the nav links, forms, and other content for toggling -->
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
              <li><a ui-sref="index" ui-sref-active="active">
                <i class="fa fa-home fa-lg"></i>&nbsp;Home</a>
              </li>
              <li ng-if="isAuthenticated && (role === 'Admin' || role === 'Faculty')" class="nga-default nga-slide-up"><a ui-sref="admin.index" ui-sref-active="active">
              <i class="fa fa-gear fa-lg"></i>&nbsp;Admin</a>
              </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li ng-if="!isAuthenticated" class="nga-default nga-slide-up">
               <a ui-sref="login"> <i class="fa fa-sign-in fa-lg"></i>&nbsp; Login</a>
             </li>
             <li ng-if="isAuthenticated" ng-cloak="" class="nga-default nga-slide-up pointer">
              <a ng-click="logOut()">
                <span class="label" ng-class="{'label-danger': role === 'Admin', 'label-warning': role === 'Faculty'}" ng-bind="role"></span> {{username}} | <i class="fa fa-sign-out fa-lg"></i> Logout</a>
              </li>
            </ul>
          </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
      </nav>
    </header>

    <!-- Main content goes here -->
    <main class="container">
      <div ui-view="" class="nga-default nga-stagger nga-slide-up" ng-cloak class="ng-cloak"></div>
    </main>


    <!-- Libraries -->
    <script src="assets/js/jquery-1.11.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/angular.js"></script>
    <script src="assets/js/angular-cookies.min.js"></script>
    <script src="assets/js/angular-animate.min.js"></script>
    <script src="assets/js/angular-sanitize.min.js"></script>
    <script src="assets/js/angular-touch.min.js"></script>
    <script src="assets/js/angular-messages.min.js"></script>
    <script src="assets/js/angular-aria.min.js"></script>
    <script src="assets/js/angular-toastr.tpls.min.js"></script>
    <script src="assets/js/angular-ui-router.min.js"></script>
    <script src="assets/js/underscore-min.js"></script>
    <script src="assets/js/angular-underscore-module.js"></script>
    <script src="assets/js/ui-bootstrap-custom-0.14.3.min.js"></script>
    <script src="assets/js/ui-bootstrap-custom-tpls-0.14.3.min.js"></script>

    <!-- Custom scritps -->
    <script src="client/app.js"></script>
    <script src="client/app.route.js"></script>

    <!-- Services -->
    <script src="client/Services/common.service.js"></script>
    <script src="client/Services/index.service.js"></script>
    <script src="client/Services/admin.service.js"></script>
    <script src="client/Services/auth.service.js"></script>
    <script src="client/Services/user.service.js"></script>
    <script src="client/Services/keyword.service.js"></script>
    <script src="client/Services/paper.service.js"></script>

    <!-- filters -->
    <script src="client/filters/paper.filter.js"></script>
    <!-- Directives -->
    <script src="client/directives/common.directive.js"></script>
    <script src="client/directives/password.directive.js"></script>

    <!-- Controllers -->
    <script src="client/controllers/index.controller.js"></script>
    <script src="client/controllers/admin.controller.js"></script>
    <script src="client/controllers/login.controller.js"></script>
    <script src="client/controllers/keyword.controller.js"></script>

    <!-- user controllers -->
    <script src="client/controllers/user/index.controller.js"></script>
    <script src="client/controllers/user/add.controller.js"></script>
    <script src="client/controllers/user/update.controller.js"></script>

    <!-- paper controller -->
    <script src="client/controllers/paper/index.controller.js"></script>
    <script src="client/controllers/paper/add.controller.js"></script>
    <script src="client/controllers/paper/update.controller.js"></script>

  </body>
  </html>