<?php
    require_once('vendor/autoload.php');
?>

<!DOCTYPE html>
<html lang="en">
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
            </ul>
          <ul class="nav navbar-nav navbar-right">
              <li data-ng-if="!isAuthenticated">
                 <a ui-sref="login"> <i class="fa fa-user fa-lg"></i>&nbsp; Login</a>
             </li>
             <li data-ng-if="isAuthenticated" ng-cloak="">
              <a data-ng-click="logOut()">
                  {{lastName }} {{firstName}} | Logout</a>
              </li>
          </ul>
        </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
</header>

<main class="container">
    <div class="jumbotron">
      <h1 class="page-lead text-center">Faculty Research Database</h1>
      <h3 class="text-center">ISTE-722 Project</h3>
  </div>
  <div data-ui-view="" class="page page-home" ng-cloak class="ng-cloak"></div>
</main>


<script src="assets/js/jquery-1.11.3.min.js"></script>
<script src="assets/js/bootstrap.min.js"></script>
<script src="assets/js/angular.min.js"></script>
<script src="assets/js/angular-cookies.min.js"></script>
<script src="assets/js/angular-animate.min.js"></script>
<script src="assets/js/angular-sanitize.min.js"></script>
<script src="assets/js/angular-touch.min.js"></script>
<script src="assets/js/angular-messages.min.js"></script>
<script src="assets/js/angular-aria.min.js"></script>
<script src="assets/js/angular-ui-router.min.js"></script>

</body>
</html>