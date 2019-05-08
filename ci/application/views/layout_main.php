<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo SITE_TITLE;?></title>
    <meta http-equiv="Content-Type" content="application/xhtml+xml; charset=utf-8" />
    <meta http-equiv="Content-Style" content="text/css" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Cache-Control" content="no-cache" />
    <meta http-equiv="expires" content="0" />
    <meta name="apple-mobile-web-app-capable" content="black" />
    <meta name="format-detection" content="telephone=no" />
    <link rel="shortcut icon" href="/static/images/favicon.ico" type="image/vnd.microsoft.icon" />
    <!-- css -->
    <link href="/static/css/base.css" rel="stylesheet" type="text/css" />
    <?php
        if (isset($css))
        {
            echo css_nocache_tag("/static/css/{$css}.css");
        }
    ?>
    <!-- js -->
    <script type="text/javascript" src="/js/jquery-2.1.0.min.js?lf=1"></script>
    <?php echo js_nocache_tag("/static/js/base.js"); ?>
</head>
<body>
<div id="contents">
    <?php echo $content_for_layout; ?>
</div>
</body>
</html>
