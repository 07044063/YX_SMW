{include file="../__header_wx.tpl"}

{assign var="script_name" value="inventorycheck"}

<div class="weui-cells__title">库存查询</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label class="weui-label">物料图号</label></div>
        <div class="weui-cell__bd">
            <input id="i_goodscode" class="weui-input" type="text" placeholder="请输入物料图号">
        </div>
        <div class="weui-cell__ft" >
            <a href="javascript:checkData();" class="weui-btn weui-btn_primary">查询库存</a>
        </div>
    </div>
</div>

<div class="page__bd">
    <div class="weui-panel weui-panel_access">
        <div id="inventorylist"></div>
    </div>
    <div id="list-loading" style="display: none;">
        <img src="{$docroot}static/images/icon/spinner-g-60.png"
             width="30">
    </div>
</div>


<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}