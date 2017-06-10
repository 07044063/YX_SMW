{include file="../__header_wx.tpl"}

{assign var="script_name" value="wxtest"}

<div id="container">
    <p class="text-title">
        下单成功！
    </p>
    <p class="text-subtitle">
        分享到朋友圈并截图发给我们
    </p>
    <p class="text-red">
        不分享就是干
    </p>
</div>

<header class="Thead">物料</header>
<div id="container">
    <dl>
        <dt>即可获得积分哦</dt>
        <dt>即可获得积分哦</dt>
        <dt>即可获得积分哦</dt>
    </dl>
</div>

<header class="Thead">状态</header>
<div id="container">
    <dl>
        <dt>即可获得积分哦</dt>
        <dt>即可获得积分哦</dt>
        <dt>即可获得积分哦</dt>
    </dl>
</div>

<a class="button" id="get_access_token" data-prom="" data-add="0">获取AccessToken</a>
<a class="button green" id="select_pic" data-prom="" data-add="0">选择图片</a>
<a class="button green" id="scan_qrcode" data-prom="" data-add="0">扫描条码</a>
<a class="button gary" data-prom="" data-add="0">无效按钮</a>

<script type="text/javascript" src="{$docroot}static/script/base/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}

