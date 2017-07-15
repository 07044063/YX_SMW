{include file="../__header_wx.tpl"}
{assign var="script_name" value="orderlist"}

<input type="hidden" value="{$status}" id="status"/>
<div id="container">
    <div class='clearfix' id='sort-bar'
         style="position: fixed;top: 43px; right: 0;left: 0; z-index:98">
         <!-- ORDER_STATUS_Z -->
        <div class='order-sort {if $status eq ''}hover{/if}' data-status="notdone"><b>未完成</b></div>
        <div class='order-sort {if $status eq 'create'}hover{/if}' data-status="create"><b>未接收</b></div>
        <div class='order-sort {if $status eq 'readying'}hover{/if}' data-status="readying"><b>未发货</b></div>
        <div class='order-sort {if $status eq 'send'}hover{/if}' data-status="send"><b>未送达</b></div>
        <div class='order-sort {if $status eq 'arrive'}hover{/if}' data-status="arrive"><b>未交货</b></div>
        <div class='order-sort {if $status eq 'delivery'}hover{/if}' data-status="delivery"><b>未确认</b></div>
        <!--<div class='order-sort {if $status eq 'done'}hover{/if}' data-status="done"><b>已完成</b></div>-->
        <div id="sort_all" class='order-sort {if $status eq 'all'}hover{/if}' data-status="all"><b>全部</b></div>
    </div>

    <div class="page__bd" style="margin-top: 37px">
        <div class="weui-panel weui-panel_access">
            <div id="orderlist"></div>
        </div>
        <div id="list-loading" style="display: none;">
            <img src="{$docroot}static/images/icon/spinner-g-60.png"
                 width="30">
        </div>
    </div>

    <div style="position: fixed;bottom: 5px;right: 5px;">
        <a href="javascript:;" class="open-popup" data-target="#full">
            <img width="38px" height="38px" src="{$docroot}static/images/search_48.png"/>
        </a>
    </div>

    <div id="full" class='weui-popup__container' style="z-index:99">
        <div class="weui-popup__overlay"></div>
        <div class="weui-popup__modal">
            <div style="margin-top: 50px">
                <div class="weui-cells__title">查询条件</div>
                <div class="weui-cells">
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label class="weui-label">单号</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" id="search_text" type="text" placeholder="请输入发货单号">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label for="name" class="weui-label">供应商</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" id="order_vendor" type="text" data-values="">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label for="name" class="weui-label">收货方</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" id="order_address" type="text">
                        </div>
                    </div>
                    <div class="weui-cell">
                        <div class="weui-cell__hd"><label for="name" class="weui-label">订单类型</label></div>
                        <div class="weui-cell__bd">
                            <input class="weui-input" id="order_type" type="text">
                        </div>
                    </div>
                </div>
                <div class="button_sp_area" style="float: right;margin-right: 20px">
                    <a href="javascript:;" class="weui-btn weui-btn_mini weui-btn_warn close-popup">取消</a>
                    <a href="javascript:;" id="reset" class="weui-btn weui-btn_mini weui-btn_default">重置</a>
                    <a href="javascript:;" id="query" class="weui-btn weui-btn_mini weui-btn_primary close-popup">查询</a>
                </div>
            </div>
        </div>
    </div>

</div>
<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js?v={$cssversion}"></script>

{include file="../__footer_wx.tpl"}
