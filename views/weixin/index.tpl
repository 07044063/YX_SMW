{include file="../__header_wx.tpl"}

{assign var="script_name" value="index"}

<div id="container">
    <p class="text-title">
        欢迎您，{$uname}
    </p>
</div>

<header class="Thead">出库</header>
<div class="weui-cells" style="margin-top: 0px">
    <a id="order_scan" class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/scan.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>扫描出库单</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
    <a id="delivery_scan" class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/truck.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>发货装车</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
    <a id="order_list" class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/list.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>发货单清单</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
</div>
<header class="Thead">退货入库</header>
<div class="weui-cells" style="margin-top: 0px">
    <a id="returing_create" class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/photo.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>创建退货单</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
    <a id="returing_list" class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/list.png" style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>退货单清单</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
</div>
<header class="Thead">信息查询</header>
<div class="weui-cells" style="margin-top: 0px">
    <a id="inventory_check" class="weui-cell weui-cell_access" href="javascript:;">
        <div class="weui-cell__hd"><img
                    src="{$docroot}static/images/weixin/inventory.png"
                    style="width:32px;margin-right:5px;display:block">
        </div>
        <div class="weui-cell__bd">
            <p>库存查询</p>
        </div>
        <div class="weui-cell__ft"></div>
    </a>
</div>

<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js?v={$cssversion}"></script>

{include file="../__footer_wx.tpl"}

