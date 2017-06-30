{include file="../__header_wx.tpl"}

<div id="container">
    {if !$order.id}
        {*<a class="weui-btn weui-btn_primary" style="margin:10px" id="scan_qrcode" data-prom="" data-add="0">扫描发货单条码</a>*}
        <p class="text-title">未找到单据资料</p>
    {else}
        <input class="hidden" id="order_id" value="{$order.id}"/>
        <input class="hidden" id="order_status" value="{$order.status}"/>
        <div class="weui-form-preview">
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">流水单号</label>
                <em class="weui-form-preview__value">&nbsp;{$order.order_serial_no}</em>
            </div>
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">类型</label>
                <em class="weui-form-preview__value">&nbsp;{$order.order_type}</em>
            </div>
            <div class="weui-form-preview__hd">
                <label class="weui-form-preview__label">状态</label>
                <em class="weui-form-preview__value">&nbsp;{$order.statusX}</em>
            </div>
            <div class="weui-form-preview__bd" style="border-bottom: 1px solid #d9d9d9;">
                <div class="weui-form-preview__item">
                    <label class="weui-form-preview__label">需求时间</label>
                    <span class="weui-form-preview__value">{$order.order_date}</span>
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
                    <label class="weui-form-preview__label">收货单位</label>
                    <span class="weui-form-preview__value">{$order.address}</span>
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
                        <button id="do_order" class="weui-btn weui-btn_primary"
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
                <div class="weui-form-preview__ft" style="margin-top: 5px">
                    <button id="order_scan" class="weui-btn weui-btn_default"
                            href="javascript:">扫描其他出库单
                    </button>
                </div>
            </div>
        </div>
    {/if}

</div>

<script>

    var signPackage = null;
    var accesstoken = 'AccessToken';

    function scanQRCode() {
        wx.scanQRCode({
            desc: 'scanQRCode desc',
            needResult: 1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
            scanType: ["barCode"], // 可以指定扫二维码还是一维码，默认二者都有
            success: function (res) {
                // 回调
                if (res.resultStr) {
                    var res_str = res.resultStr.split(',');
                    location.href = '?/Wxpage/order/order_code=' + res_str[1];
                }
            },
            error: function (res) {
                if (res.errMsg.indexOf('function_not_exist') > 0) {
                    $.alert('版本过低请升级')
                }
            }
        });
    }

    //微信JSSDK签名获取
    var url = window.location.href;

    $.get('?/Weixin/getSignPackage/', {
                url: url
            }, function (r) {
                if (r.ret_code == 0) {
                    signPackage = r.ret_msg;
                    wx.config({
                        debug: false,
                        appId: signPackage['appid'],
                        timestamp: signPackage['timestamp'],
                        nonceStr: signPackage['noncestr'],
                        signature: signPackage['signature'],
                        jsApiList: [
                            // 所有要调用的 API 都要加到这个列表中
                            'scanQRCode'
                        ]
                    });
                    wx.ready(function () {
                    });
                    wx.error(function (res) {
                    });
                }
            }
    );

    $('#order_scan').click(function () {
        scanQRCode();
    });

    $('#do_order').click(function () {
        $.showLoading();
        $.post('?/Weixin/changeOrderStatus/', {
            order_id: $('#order_id').val(),
            oldstatus: $('#order_status').val()
        }, function (r) {
            $.hideLoading();
            if (!r.ret_code == 0) {
                $.alert('操作失败 ' + order_error_list[r.ret_code]);
            } else {
                $.alert("操作成功", function () {
                    //点击确认后的回调函数
                    location.reload();
                });
            }
        });
    });
</script>

{include file="../__footer_wx.tpl"}