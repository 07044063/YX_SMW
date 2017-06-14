{include file="../__header_wx.tpl"}

{assign var="script_name" value="send"}

<div class="weui-cells__title">请选择车辆信息</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label for="name" class="weui-label">车辆</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" id="truck_select" type="text" value="" data-values="">
        </div>
    </div>
</div>

<div class="weui-cells__title">扫描添加发货单</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__hd">
            <label class="weui-label">当前发货单</label>
        </div>
        <div class="weui-cell__bd">
            <label id="listcount" class="weui-label"></label>
        </div>
        <div class="weui-cell__ft">
            <button id="begin_scan" class="weui-vcode-btn">开始扫描</button>
        </div>
    </div>
</div>

<div id="orderlist" class="weui-cells"></div>

<button id="test_btn" class="weui-vcode-btn">测试添加数据</button>

<div class="bottomWrap clearfix">
    <div class="weui-form-preview__ft">
        <button id="do_order" class="weui-btn weui-btn_default"
                href="javascript:">发货
        </button>
    </div>
</div>

<textarea id='order_temp' style='display:none;'>
<!-- 模板部分 -->
    {literal}
        <%for(var i=0;i < list.length;i++){%>
        <div class="weui-cell" id="od<%=list[i].id%>" data-id="<%=list[i].id%>" data-code="<%=list[i].order_code%>"
             href="javascript:">
            <div class="weui-cell__hd">
                <label class="weui-label"><%=list[i].order_type%></label>
            </div>
            <div class="weui-cell__bd">
                <p class="weui-label"><%=list[i].order_serial_no%></p>
            </div>
            <div class="weui-cell__ft">
                <p class="weui-label"><%=list[i].order_code%></p>
            </div>
        </div>
        <%}%>
    {/literal}
    <!-- 模板结束 -->
</textarea>

<script type="text/javascript" src="{$docroot}static/script/lib/baiduTemplate.js"></script>
<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}

