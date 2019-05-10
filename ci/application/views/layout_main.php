<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo SITE_TITLE;?></title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <meta http-equiv="Content-Style" content="text/css" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="apple-mobile-web-app-capable" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="shortcut icon" href="/static/images/favicon.ico" type="image/vnd.microsoft.icon" />
    <!-- css -->
    <link href="/static/css/base.css" rel="stylesheet" type="text/css" />
    <link href="/static/css/bootstrap/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <?php
        if (isset($css))
        {
            echo css_nocache_tag("/static/css/{$css}.css");
        }
    ?>
    <!-- jQuery読み込み -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <?php echo js_nocache_tag("/static/js/base.js"); ?>
    <script type="text/javascript" src="/static/js/bootstrap/bootstrap.min.js"></script>
</head>
<body>
<div id="contents">
    <?php echo $content_for_layout; ?>
</div>
</body>
</html>
