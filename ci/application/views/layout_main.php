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
    <link rel="shortcut icon" href="/favicon.ico" type="image/vnd.microsoft.icon" />
    <!-- css -->
    <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
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
    <?php
        echo js_nocache_tag("/assets/js/base.js");

        if (isset($javascript))
        {
            echo js_nocache_tag("/assets/js/{$javascript}.js");
        }
    ?>
</head>
<body class="sidebar-collapse">
    <nav class="navbar navbar-expand-lg bg-primary">
        <div class="container">
            <a class="navbar-brand" href="/top/index"><?php echo SITE_TITLE;?></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
            </button>
            <div class="collapse navbar-collapse justify-content-end" id="navbar" data-nav-image="assets/img/blurred-image-1.jpg">
                <?php if (isset($member_id) && !empty($member_id)) { ?>
                    <form class="form-inline my-4 mx-3 my-lg-0" action="/search/all" method="POST">
                        <input type="hidden" name="<?php echo $csrf['name'];?>" value="<?php echo $csrf['hash'];?>" />
                        <input class="form-control" name="keyword" type="search" placeholder="Search" aria-label="Search">
                    </form>
                    <ul class="navbar-nav mt-2">
                        <li class="nav-item dropdown">
                            <?php if (!empty($notification)) { ?>
                                <a href="#" class="nav-link" idbutton="icon-information" data-toggle="dropdown">
                                    <i class="now-ui-icons ui-1_bell-53" aria-hidden="true"></i>
                                    <span class="badge badge-pill badge-danger"><?php echo count($notification); ?></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-left" aria-labelledby="icon-information">
                                    <ul class="navbar-nav">
                                        <li class="nav-item">
                                            <?php foreach($notification as $v) { ?>
                                                <a class="dropdown-item" href="<?php echo $v["link"];?>"><?php echo $v["text"];?></a>
                                            <?php } ?>
                                        </li>
                                    </ul>
                                </div>
                            <?php } else { ?>
                                <a class="nav-link">
                                    <i class="now-ui-icons ui-1_bell-53" aria-hidden="true"></i>
                                </a>
                            <?php } ?>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/err/under_construction">
                                <p>League</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/err/under_construction">
                                <p>Scrim</p>
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" id="navbarSearchBoardMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <p>
                                    Search
                                </p>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarSearchBoardMenuLink">
                                <a class="dropdown-item" href="/search/member">Members</a>
                                <a class="dropdown-item" href="/search/team">Teams</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" id="navbarAccountMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <p>
                                    Account
                                </p>
                            </a>
                            <div class="dropdown-menu" aria-labelledby="navbarAccountMenuLink">
                                <a class="dropdown-item" href="/account/profile/<?php echo $member_id;?>">Profile</a>
                                <a class="dropdown-item" href="/team/detail">Team</a>
                                <a class="dropdown-item" href="/account/logout">Sign out</a>
                            </div>
                        </li>
                    </ul>
                <?php } else { ?>
                    <ul class="navbar-nav mt-2">
                        <li class="nav-item">
                            <a class="nav-link" href="/account/login_form">
                                <p>SIGN IN</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/account/register_form">
                                <p>SIGN UP</p>
                            </a>
                        </li>
                    </ul>
                <?php } ?>
            </div>
        </div>
    </nav>
    <?php if (!empty($alerts)) { ?>
        <div class="alert <?php echo $alerts["class"];?>" role="alert">
            <div class="container">
                <div class="alert-icon">
                    <i class="now-ui-icons ui-1_bell-53"></i>
                </div><?php echo $alerts["message"];?><button type="button" class="close" data-dismiss="alert" aria-label="Close">
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
    <footer class="footer">
        <div class="container">
            <p class="copyright">© 2019 TEAM LEAGUE created by Ichimatsu</p>
        </div>
    </footer>
</body>
</html>
