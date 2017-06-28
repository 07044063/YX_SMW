{include file="../__header_wx.tpl"}
{assign var="script_name" value="orderlist"}

<input type="hidden" value="{$status}" id="status"/>
<div id="container">
    <div class='clearfix' id='sort-bar'>
        <div class='order-sort {if $status eq ''}hover{/if}' data-status="notdone"><b>未完成</b></div>
        <div class='order-sort {if $status eq 'create'}hover{/if}' data-status="create"><b>未接收</b></div>
        <div class='order-sort {if $status eq 'readying'}hover{/if}' data-status="readying"><b>未发货</b></div>
        <div class='order-sort {if $status eq 'send'}hover{/if}' data-status="send"><b>已发货</b></div>
        <div class='order-sort {if $status eq 'delivery'}hover{/if}' data-status="delivery"><b>已收货</b></div>
        <div class='order-sort {if $status eq 'done'}hover{/if}' data-status="done"><b>已完成</b></div>
        <div class='order-sort {if $status eq 'all'}hover{/if}' data-status="all"><b>全部</b></div>
    </div>

    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div id="orderlist"></div>
        </div>
        <div id="list-loading" style="display: none;">
            <img src="{$docroot}static/images/icon/spinner-g-60.png"
                 width="30">
        </div>
    </div>

</div>
<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}