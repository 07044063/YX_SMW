{include file="../__header_wx.tpl"}

{assign var="script_name" value="inventorycheck"}
<div id="container">
    <div class="search-w-box">
        <input style="width: 70%"
               id="i_goodscode"
               class="search-w-input"
               placeholder="输入物料图号查询"/>
        <a style="position: absolute;top: 12px;right: 12px;" href="javascript:checkData();"
           class="weui-btn weui-btn_mini weui-btn_primary">查询</a>
    </div>

    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div id="inventorylist" style="margin-right: 10px; margin-left: 10px;"></div>
        </div>
        <div id="list-loading" style="display: none;">
            <img src="{$docroot}static/images/icon/spinner-g-60.png"
                 width="30">
        </div>
    </div>
</div>

<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js?v={$cssversion}"></script>

{include file="../__footer_wx.tpl"}