<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title><?php echo SITE_TITLE;?></title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <meta http-equiv="Content-Style" content="text/css" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
    <meta name="apple-mobile-web-app-capable" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/vnd.microsoft.icon" />
    <!-- css -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<!--    <link href="/assets/css/now-ui-kit.css" rel="stylesheet" />-->
<!--    <link href="/assets/css/base.css" rel="stylesheet" type="text/css" />-->
    <?php
        echo css_nocache_tag("/assets/css/now-ui-kit.css");
        echo css_nocache_tag("/assets/css/base.css");

        if (isset($css))
        {
            echo css_nocache_tag("/assets/css/{$css}.css");
        }
    ?>
    <!-- jQuery読み込み -->
    <!--   Core JS Files   -->
    <script src="/assets/js/core/jquery.min.js" type="text/javascript"></script>
    <script src="/assets/js/core/popper.min.js" type="text/javascript"></script>
    <script src="/assets/js/core/bootstrap.min.js" type="text/javascript"></script>
    <!--  Plugin for Switches, full documentation here: http://www.jque.re/plugins/version3/bootstrap.switch/ -->
    <script src="/assets/js/plugins/bootstrap-switch.js"></script>
    <!--  Plugin for the Sliders, full documentation here: http://refreshless.com/nouislider/ -->
    <script src="/assets/js/plugins/nouislider.min.js" type="text/javascript"></script>
    <!--  Plugin for the DatePicker, full documentation here: https://github.com/uxsolutions/bootstrap-datepicker -->
    <script src="/assets/js/plugins/bootstrap-datepicker.js" type="text/javascript"></script>
    <!-- Control Center for Now Ui Kit: parallax effects, scripts for the example pages etc -->
    <script src="/assets/js/now-ui-kit.js?v=1.3.0" type="text/javascript"></script>
    <!-- User js-->
    <?php echo js_nocache_tag("/assets/js/base.js"); ?>
</head>
<body class="sidebar-collapse">
<nav class="navbar navbar-expand-lg bg-primary">
        <a class="navbar-brand" href="/top/index"><?php echo SITE_TITLE;?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#example-navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="example-navbar" data-nav-image="assets/img/blurred-image-1.jpg">
            <ul class="navbar-nav">
                <?php if (isset($member_id) && !empty($member_id)) { ?>
                    <li class="nav-item">
                        <form action="#" method="post" style="margin: auto 7px;">
                            <div class="input-group no-border" style="margin-bottom: 0px;">
                                <div class="input-group-prepend search-keyword-form">
                                  <span class="input-group-text">
                                    <i class="now-ui-icons ui-1_zoom-bold"></i>
                                  </span>
                                </div>
                                <input type="text" class="form-control search-keyword-form" style="padding: 11px 5px;" name="keyword" placeholder="keyword">
                            </div>
                        </form>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <p>League</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">
                            <p>Scrim</p>
                        </a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarSearchBoardMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <p>
                                Search Board
                            </p>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarSearchBoardMenuLink">
                            <a class="dropdown-item" href="#">Members</a>
                            <a class="dropdown-item" href="#">Teams</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" id="navbarAccountMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <p>
                                Account
                            </p>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarAccountMenuLink">
                            <a class="dropdown-item" href="/account/profile">Profile</a>
                            <a class="dropdown-item" href="#">Team</a>
                            <a class="dropdown-item" href="/account/logout">Logout</a>
                        </div>
                    </li>
                <?php } else { ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/account/login_form">
                            <p>Login</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/account/regist_form">
                            <p>Regist</p>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        </div>
</nav>
<?php if (!empty($notification)) { ?>
    <div class="alert <?php echo $notification["class"];?>" role="alert">
        <div class="container">
            <div class="alert-icon">
                <i class="now-ui-icons ui-1_bell-53"></i>
            </div><?php echo $notification["message"];?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">
                <i class="now-ui-icons ui-1_simple-remove"></i>
              </span>
            </button>
        </div>
    </div>
<?php } ?>
<div class="wrapper">
    <?php echo $content_for_layout; ?>
</div>
</body>
</html>
