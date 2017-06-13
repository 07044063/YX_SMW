{include file="../__header_wx.tpl"}

{assign var="script_name" value="delivery"}

<div class="weui-cells__title">请选择车辆信息</div>
<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__hd"><label for="name" class="weui-label">车辆</label></div>
        <div class="weui-cell__bd">
            <input class="weui-input" id="truck_select" type="text" value="" data-values="" readonly="">
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
            <label class="weui-label">0张</label>
        </div>
        <div class="weui-cell__ft">
            <button id="begin_scan" class="weui-vcode-btn">开始扫描</button>
        </div>
    </div>
</div>

<div id="orderDetailsWrapper" data-minheight="68px"></div>

<div class="weui-cells">
    <div class="weui-cell">
        <div class="weui-cell__hd">
            <label class="weui-label">发货单34565</label>
        </div>
    </div>
    <div class="weui-cell">
        <div class="weui-cell__hd">
            <label class="weui-label">发货单34565</label>
        </div>
    </div>
</div>

<div class="bottomWrap clearfix">
    <div class="weui-form-preview__ft">
        <button id="do_order" class="weui-form-preview__btn weui-form-preview__btn_primary"
                href="javascript:">发货
        </button>
    </div>
</div>

<textarea id='order_temp' style='display:none;'>
<!-- 模板部分 -->
    {literal}
        <%for(var i=0;i < list.length;i++){%>

        <% var carts = list[i].cart_datas; %>

        <%for(var j=0;j < carts.length;j++){%>

        <section class="cartListWrap clearfix" id="cartsec<%=carts[j].product_id%>">
            <input type="hidden" value="<%=carts[j].envstr%>" id="pd-envs-<%=carts[j].product_id%>"
                   data-pid="<%=carts[j].product_id%>" class="pd-envstr"/>
            <img alt="<%=carts[j].product_name%>" width="100" height="100" src="<%=carts[j].catimg%>"/>

            <div class="cartListDesc">
                <p class="title">
                    <%=carts[j].product_name%>
                </p>

                <p class="count">
                    <span class="spec Elipsis">编号：<%=carts[j].product_code%></span></p>

                <p class="count">

                    <span class="spec Elipsis">作者：<%=carts[j].product_subname%></span>
                    <!--                <span class="spec Elipsis"><%=carts[j].specname%></span>-->
                    <!--						<span class="dprice prices"-->
                    <!--                              data-expfee="{$product_list[i].product_expfee}"-->
                    <!--                              data-price="<%=carts[j].sale_price%>"-->
                    <!--                              data-weight="<%=carts[j].product_weight%>"-->
                    <!--                              data-count="<%=carts[j].count%>">&yen; <%=carts[j].sale_price%>-->
                    <!--						</span>-->
                </p>
                <dl class="pd-dsc clearfix">
                    <dt style="display: none" class="productCount clearfix" data-pid="<%=carts[j].product_id%>"
                        data-spid="<%=carts[j].spec_id%>">
                        <a class="btn productCountMinus" href='javascript:;'></a>
						<span class="productCountNum">
							<input type='tel'
                                   data-prom-limit="0"
                                   value="<%=carts[j].count%>"
                                   class="dcount productCountNumi"/>
						</span>
                        <a class="btn productCountPlus" href='javascript:;'></a>
                    </dt>
                </dl>
                <a class="cartDelbtn" data-pdid="<%=carts[j].product_id%>" data-spid="<%=carts[j].spec_id%>"></a>
            </div>
        </section>

        <%}%>

        <%}%>
    {/literal}
<!-- 模板结束 -->
</textarea>

<script type="text/javascript" src="{$docroot}static/script/lib/baiduTemplate.js"></script>
<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}

