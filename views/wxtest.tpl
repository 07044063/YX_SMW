<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit">
    <title></title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black"/>
    <meta name="format-detection" content="telephone=no"/>
    <link href="{$docroot}favicon.ico" rel="Shortcut Icon"/>
    <link href="{$docroot}static/css/bootstrap/bootstrap.css" type="text/css" rel="Stylesheet"/>
    <link href="{$docroot}static/css/base/base_style.css" type="text/css" rel="Stylesheet"/>
    <link href="{$docroot}static/fontaswsome/css/font-awesome.min.css" type="text/css" rel="Stylesheet"/>
    <script type="text/javascript" src="{$docroot}static/script/lib/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="{$docroot}static/script/lib/angularjs/angular.min.js"></script>
    <script type="text/javascript" src="{$docroot}static/script/lib/bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{$docroot}static/script/service/util_service.js"></script>

</head>
<body>

{assign var="script_name" value="wxtest"}

<div class="pd15" ng-controller="wxtestController" ng-app="ngApp">
    {literal}
        <div class="fv2Field clearfix">
            <div class="fv2Left">
                <span>1.</span>
            </div>
            <div class="fv2Right">
                <button class="btn btn-success" ng-click="getAccessToken()">获取AccessToken</button>
                <div class='fv2Tip'>{{accesstoken}}</div>
            </div>
        </div>
    {/literal}
</div>

<script type="text/javascript" src="{$docroot}static/script/base/{$script_name}.js"></script>

</body>
</html>