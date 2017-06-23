{include file="../__header_wx.tpl"}
{assign var="script_name" value="returninglist"}

<input type="hidden" value="{$status}" id="status"/>
<input type="hidden" value="{$auth}" id="auth"/>
<div id="container">
    <div class='clearfix' id='sort-bar'>
        <div class='return-sort {if $status eq ''}hover{/if}' data-status=""><b>全部</b></div>
        <div class='return-sort {if $status eq 'create'}hover{/if}' data-status="create"><b>新创建</b></div>
        <div class='return-sort {if $status eq 'receive'}hover{/if}' data-status="receive"><b>已入库</b></div>
        <div class='return-sort {if $status eq 'done'}hover{/if}' data-status="done"><b>已记账</b></div>
    </div>

    <div class="page__bd">
        <div class="weui-panel weui-panel_access">
            <div class="weui-panel__bd">
                <div id="returninglist"></div>
            </div>
        </div>
        <div id="list-loading" style="display: none;">
            <img src="{$docroot}static/images/icon/spinner-g-60.png"
                 width="30">
        </div>
    </div>

</div>
<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}