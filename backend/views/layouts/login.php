
<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use common\widgets\Alert;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Hệ thống quản lý kho hàng</title>

  <!-- bootstrap -->
  <link rel="stylesheet" href="assests/bootstrap/css/bootstrap.min.css">
  <!-- bootstrap theme-->
  <link rel="stylesheet" href="assests/bootstrap/css/bootstrap-theme.min.css">
  <!-- font awesome -->
  <link rel="stylesheet" href="assests/font-awesome/css/font-awesome.min.css">

  <!-- custom css -->
  <link rel="stylesheet" href="custom/css/custom.css">  

  <!-- jquery -->
  <script src="assests/jquery/jquery.min.js"></script>
  <!-- jquery ui -->  
  <link rel="stylesheet" href="assests/jquery-ui/jquery-ui.min.css">
  <script src="assests/jquery-ui/jquery-ui.min.js"></script>

  <!-- bootstrap js -->
  <script src="assests/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container">
    <header style="width: 100%">
      <nav class="navbar navbar-default">
        <div class="container-fluid">
          <div class="navbar-header">
            <a class="navbar-brand" href=""><span class="list-ul" style="color: red">The Book Store Admin Panel</span></a>
          </div>
          <ul class="nav navbar-nav">


          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="/cafe/frontend/web"><span class="glyphicon glyphicon-home"></span> Quay lại Trang chủ</a></li>
            <li><a href=""><span class="glyphicon glyphicon-log-in"></span> Đăng Nhập</a></li>
          </ul>
        </div>
      </nav>
    </header>
    
    <?=$content?>
   
      <div class="container" style="margin-top: 330px">
        <div class="row">
          <hr>
          <div class="col-lg-12">
            <div class="col-md-8">
              <b><span>Thực tập HOUSE3D</span> | <span>Nhóm X</span> </b>   
            </div>
            <div class="col-md-4">
              <b><p class="muted pull-right">©BDD</p></b>
            </div>
          </div>
        </div>
      </div>


      <!-- /col-md-4 -->
    </div>
    <!-- /row -->
  </div>
  <!-- container -->  
  
</body>
</html>






