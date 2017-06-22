<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit">
    <title>{$settings.sitename}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <link href="{$docroot}favicon.ico" rel="Shortcut Icon"/>
    <link href="{$docroot}static/css/bootstrap/bootstrap.css" type="text/css" rel="Stylesheet"/>
    <link href="{$docroot}static/css/base/index.css?v={$cssversion}" type="text/css" rel="Stylesheet"/>
    <link href="{$docroot}static/fontaswsome/css/font-awesome.min.css" type="text/css" rel="Stylesheet"/>
    <script type="text/javascript" src="{$docroot}static/script/lib/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="{$docroot}static/script/lib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{$docroot}static/script/base/index.js?v={$cssversion}"></script>
</head>
<body class="wdmin-main" style="overflow:hidden;">
<!-- 管理控制台主页面 -->
<nav class="navbar navbar-default" id="navtop">
    <div class="container-lg">
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <div class="pull-left" style="line-height: 43px;color: #fff;padding-left: 10px;">{$settings.sitename}
                - {$adname}
                （{$today}） {$config.system_version}
            </div>
            <ul class="nav navbar-nav navbar-right">
                <!-- @see http://v3.bootcss.com/components/ -->
                <li class="topRightNavItem">
                    <a href="//220.178.49.216/acegilogin.jsp" target="_blank">二厂</a>
                </li>
                <li class="topRightNavItem">
                    <a href="//220.178.49.216:34761/UniMaxMds/login.action" target="_blank">三厂</a>
                </li>
                <li class="topRightNavItem">
                    <a href="//220.178.49.197:8080/JACSRM/default.aspx" target="_blank">交流平台</a>
                </li>
                <li class="topRightNavItem">
                    <a href="//supplier.yf.sh.cn" target="_blank">延峰</a>
                </li>
                <li class="topRightNavItem">
                    <a href="?/Index/logOut/"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span>退出</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div id="wdmin-wrap">
    <div id="leftNav">{include file="./navs.tpl"}</div>
    <div id="rightWrapper">
        <div id="main-mid">
            <div id="iframe_loading"><img src="{$docroot}static/images/icon/iconfont-loading-x64-green.png"/></div>
            <div id="__subnav__"></div>
            <iframe id="right_iframe" src="" width="100%" frameborder="0"></iframe>
        </div>
    </div>
</div>
</body>
</html>