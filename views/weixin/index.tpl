{include file="../__header_wx.tpl"}

{assign var="script_name" value="index"}

<header class="Thead">发货</header>
<div class="weui-cells">
    <a id="order_scan" class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/scan.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>扫描发货单</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
    <a class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/scan.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>发货装车</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
</div>
<header class="Thead">查询</header>
<div class="weui-cells">
    <a class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/scan.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>cell standard</p>
        </div>
        <div class="weui-cell__ft">说明文字</div>
    </a>
    <a class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/scan.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>cell standard</p>
        </div>
        <div class="weui-cell__ft">说明文字</div>
    </a>
</div>

<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}

