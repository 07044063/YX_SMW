{include file="../__header_wx.tpl"}

{assign var="script_name" value="wxtest"}

<div id="container">
    <p class="text-title">
        测试标题
    </p>
    <p class="text-subtitle">
        测试子标题
    </p>
    <p class="text-red">
        这是红色字体
    </p>
</div>

<header class="Thead">章节标题1</header>
<div id="container">
    <p>
        章节内容
    </p>
</div>

<a class="weui-btn weui-btn_primary" style="margin:10px" id="get_access_token" data-prom=""
   data-add="0">获取AccessToken</a>
<a class="weui-btn weui-btn_warn" style="margin:10px" id="select_pic" data-prom="" data-add="0">选择图片</a>
<a class="weui-btn weui-btn_default" style="margin:10px" id="scan_qrcode" data-prom="" data-add="0">扫描条码</a>
<a class="weui-btn weui-btn_disabled weui-btn_primary" style="margin:10px" data-prom="" data-add="0">无效按钮</a>

<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}

