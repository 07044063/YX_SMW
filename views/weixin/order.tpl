{include file="../__header_wx.tpl"}

{assign var="script_name" value="order"}

<input type="hidden" value="{$status}" id="status"/>

<div id="container">
    {if {$order_code}}
        <a class="weui-btn weui-btn_primary" style="margin:10px" id="scan_qrcode" data-prom="" data-add="0">扫描发货单条码</a>
    {else}
        <input class="hidden" id="order_id" value="{$order.id}"/>
        <input class="hidden" id="order_status" value="{$order.status}"/>
        <div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">流水单号</label>
                <em class="weui-form-preview__value">&nbsp;{$order.order_serial_no}</em>
            </div>
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">状态</label>
                <em class="weui-form-preview__value">&nbsp;{$order.statusX}</em>
            </div>
            <div class="weui-form-preview__bd" style="border-bottom: 1px solid #d9d9d9;">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">客户</label>
                    <span class="weui-form-preview__value">{$order.customer_name}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">收货单号</label>
                    <span class="weui-form-preview__value">{$order.order_code}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">供货商</label>
                    <span class="weui-form-preview__value">{$order.vendor_name}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">送达时间</label>
                    <span class="weui-form-preview__value">{$order.order_date}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">道口</label>
                    <span class="weui-form-preview__value">{$order.dock}</span>
                </div>
            </div>
            <div class="weui-form-preview__bd">
                {foreach from=$order.goods item=od name=ods}
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">{$od.goods_name}</label>
                        <span class="weui-form-preview__value">{$od.needs_sum}</span>
                    </div>
                {/foreach}
            </div>
            <div class="bottomWrap clearfix">
                <div class="weui-form-preview__ft">
                    {*<button id="do_recorder" class="weui-form-preview__btn weui-form-preview__btn_default"*}
                    {*href="javascript:">操作记录*}
                    {*</button>*}
                    {if {$order.hasauth} > 0}
                        <button id="do_order" class="weui-btn weui-btn_default"
                                href="javascript:">
                            {if {$order.status} == 'create'}
                                仓库接收
                            {/if}
                            {if {$order.status} == 'receive'}
                                备货完成
                            {/if}
                            {if {$order.status} == 'ready'}
                                对点完成
                            {/if}
                            {*{if {$order.status} == 'check'}*}
                            {*发货完成*}
                            {*{/if}*}
                            {if {$order.status} == 'send'}
                                交货完成
                            {/if}
                            {if {$order.status} == 'delivery'}
                                全部完成
                            {/if}
                        </button>
                    {/if}
                </div>
            </div>
        </div>
    {/if}

</div>

<script type="text/javascript" src="{$docroot}static/script/weixin/{$script_name}.js"></script>

{include file="../__footer_wx.tpl"}