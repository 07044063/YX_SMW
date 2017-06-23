{include file="../__header_wx.tpl"}

<div id="container">
    {if !$back.id}
        {*<a class="weui-btn weui-btn_primary" style="margin:10px" id="scan_qrcode" data-prom="" data-add="0">扫描发货单条码</a>*}
        <p class="text-title">未找到单据资料</p>
    {else}
        <input class="hidden" id="back_id" value="{$back.id}"/>
        <div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">退回单号</label>
                <em class="weui-form-preview__value">&nbsp;{$back.back_code}</em>
            </div>
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">类型</label>
                <em class="weui-form-preview__value">&nbsp;{$back.back_typeX}</em>
            </div>
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">状态</label>
                <em class="weui-form-preview__value">&nbsp;{$back.statusX}</em>
            </div>
            <div class="weui-form-preview__bd" style="border-bottom: 1px solid #d9d9d9;">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">供货商</label>
                    <span class="weui-form-preview__value">{$back.vendor_name}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">退回日期</label>
                    <span class="weui-form-preview__value">{$back.back_date}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">地址</label>
                    <span class="weui-form-preview__value">{$back.address}</span>
                </div>
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">联系方式</label>
                    <span class="weui-form-preview__value">{$back.contacts}</span>
                </div>
            </div>
            <div class="weui-form-preview__bd">
                {foreach from=$back.goods item=od name=ods}
                    <div class="weui-form-preview__item">
                        <label class="weui-form-preview__label">{$od.goods_ccode}&nbsp;&nbsp;{$od.goods_name}</label>
                        <span class="weui-form-preview__value">{$od.needs}</span>
                    </div>
                {/foreach}
            </div>
            <div class="bottomWrap clearfix">
                <div class="weui-form-preview__ft">
                    {*<button id="do_recorder" class="weui-form-preview__btn weui-form-preview__btn_default"*}
                    {*href="javascript:">操作记录*}
                    {*</button>*}
                    {if {$back.hasauth} > 0}
                        <button id="do_back" class="weui-btn weui-btn_default"
                                href="javascript:">
                            {if {$back.status} == 'create'}
                                仓库接收
                            {/if}
                            {if {$back.status} == 'receive'}
                                发货完成
                            {/if}
                            {if {$order.status} == 'send'}
                                确认完成
                            {/if}
                        </button>
                    {/if}
                </div>
            </div>
        </div>
    {/if}

</div>

<script>
    $('#do_back').click(function () {
        $.showLoading();
        $.post('?/Weixin/changeBackStatus/', {
            back_id: $('#back_id').val()
        }, function (r) {
            $.hideLoading();
            if (!r.ret_code == 0) {
                $.alert('操作失败 ' + r.ret_msg);
            } else {
                $.toast("操作成功");
                location.reload();
            }
        });
    });
</script>

{include file="../__footer_wx.tpl"}