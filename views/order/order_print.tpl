<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="renderer" content="webkit">
    <title>出库单打印</title>
    <link href="{$docroot}favicon.ico" rel="Shortcut Icon"/>
    <link rel="stylesheet" type="text/css" href="{$docroot}static/css/bootstrap/bootstrap.css"/>
    {*<link rel="Stylesheet" type="text/css" href="{$docroot}static/css/base/base_style.css?v={$cssversion}"/>*}
    <script type="text/javascript" src="{$docroot}static/script/lib/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="{$docroot}static/script/lib/JsBarcode.all.js"></script>
</head>
<body>

<style media="print">
    .noprint {
        display: none
    }
</style>

<style>
    table tr td, table tr th {
        padding: 5px 0px 0px 5px;
        text-align: left
    }
</style>

<div class="noprint" style="width:200px;font-size:12px;text-align:left;padding: 15px">
    <input type="button" value="打印本页" onclick="javascipt:window.print()">
    <input id="order_code" class="hidden" value="{$order.order_code}">
    <input id="order_date" class="hidden" value="{$order.order_date}">
    <input id="back_code" class="hidden" value="{$back.back_code}">
    <input id="back_date" class="hidden" value="{$back.back_date}">
</div>

<div style="width: 980px">

    <div align=center style="width:850px;float:left;padding-top: 10px;padding-bottom: 20px;min-height: 1100px">
        <div align=center style="font-size:22px;padding: 10px 10px 10px 30px">安徽亿翔仓储服务有限公司</div>

        {if $order.order_code}
            <table style="width:800px;">
                <tr>
                    <td width=60% colspan=4 style='border:1px;border-style:solid hidden solid solid;'>
                        <p><span style="font-size:22px">
                            {if $order.address == '江淮四工厂'}
                                多功能商用车公司采购送货单
                            {else}
                                {$order.address}送货单
                            {/if}
                        </span></p>
                        <p id="order_date_p" style="font-size:18px"></p>
                    </td>
                    <td width=40% colspan=5 style='border:solid 1px'>
                        <div align=center>
                            <svg id="barcode"></svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width=60% colspan=4 style='border:solid 1px;'>
                        <p style="font-size:14px">
                            {if $order.address == '江淮四工厂'}
                                送货单位：
                            {else}
                                供应商：
                            {/if}
                            {$order.vendor_name}</p>
                    </td>
                    <td width=40% colspan=5 style='border:solid 1px'>
                        <p style="font-size:14px"> 收货单号：{$order.order_code}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan=4 style='max-width:450px;border:solid 1px'>
                        <p style="font-size:14px">收货单位：
                            {if $order.address == '江淮四工厂'}
                                安徽江淮多功能商用车分公司
                            {else}
                                {$order.address}
                                {if $order.contacts.length != ''}
                                    <br>
                                    联系方式：{$order.contacts}
                                {/if}
                            {/if}
                        </p>
                    </td>
                    <td colspan=5 style='max-width:350px;border:solid 1px;line-height:22px'>
                        <p style="font-size:14px">
                            {if $order.address == '江淮四工厂'}
                                采购订单号：{$order.pur_no}
                                <br>
                            {else}
                                {if $order.pur_no != ''}
                                    采购订单号：{$order.pur_no}
                                    <br>
                                {/if}
                            {/if}
                            供应商代码：{$order.vendor_code}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style='max-width:35px;border:solid 1px;line-height:20px'>
                        <p style="font-size:14px">No.</p>
                    </td>
                    <td style='max-width:150px;border:solid 1px'>
                        <p style="font-size:14px">物料图号</p>
                    </td>
                    <td style='max-width:230px;border:solid 1px'>
                        <p style="font-size:14px">物料名称</p>
                    </td>
                    <td style='max-width:35px;border:solid 1px'>
                        <p style="font-size:14px">单位</p>
                    </td>
                    <td style='max-width:75px;border:solid 1px'>
                        <p style="font-size:14px">
                            {if $order.address == '江淮四工厂'}
                                送货数量
                            {else}
                                订单数量
                            {/if}
                        </p>
                    </td>
                    <td style='max-width:75px;border:solid 1px'>
                        <p style="font-size:14px">
                            {if $order.address == '江淮四工厂'}
                                送检数量
                            {else}
                                实发数量
                            {/if}
                        </p>
                    </td>
                    <td style='max-width:75px;border:solid 1px'>
                        <p style="font-size:14px">送检结果</p>
                    </td>
                    <td style='max-width:75px;border:solid 1px'>
                        <p style="font-size:14px">实收数量</p>
                    </td>
                    <td style='max-width:50px;border:solid 1px'>
                        <p style="font-size:14px">备注</p>
                    </td>
                </tr>
                {*{foreach $gds as $gd}*}
                {foreach from=$gds item=gd name=gdn}
                    <tr>
                        <td style='max-width:35px;border:solid 1px'>
                            <p style="font-size:14px">{$smarty.foreach.gdn.index + 1}
                            </p>
                        </td>
                        <td style='max-width:150px;border:solid 1px;word-wrap:break-word; word-break:normal;'>
                            <p style="font-size:14px;">{$gd.goods_ccode}</p>
                        </td>
                        <td style='max-width:230px;border:solid 1px'>
                            <p style="font-size:14px;word-break: break-all;">{$gd.goods_name}</p>
                        </td>
                        <td style='max-width:35px;border:solid 1px'>
                            <p style="font-size:14px">{$gd.using_count}</p>
                        </td>
                        <td style='max-width:75px;border:solid 1px'>
                            <p style="font-size:14px">{$gd.needs}</p>
                        </td>
                        <td style='max-width:75px;border:solid 1px'>
                            <p style="font-size:14px">{$gd.sends}</p>
                        </td>
                        <td style='max-width:75px;border:solid 1px'>
                            <p style="font-size:14px"></p>
                        </td>
                        <td style='max-width:75px;border:solid 1px'>
                            <p style="font-size:14px"></p>
                        </td>
                        <td style='max-width:50px;border:solid 1px'>
                            <p style="font-size:14px">{$gd.remark}</p>
                        </td>
                    </tr>
                {/foreach}
            </table>
        {/if}

        {if $back.back_code}
            <table style="width:800px;">
                <tr>
                    <td width=60% colspan=3 style='border:1px;border-style:solid hidden solid solid;'>
                        <p><span style="font-size:22px">
                            安徽亿翔 货物退回单
                                {if $back.back_type == 1}
                                    （良品）
                                {elseif $back.back_type == 2}
                                    （不良品）
                                {else}
                                    （器具/料盒）
                                {/if}
                        </span></p>
                        <p id="back_date_p" style="font-size:18px"></p>
                    </td>
                    <td width=40% colspan=4 style='border:solid 1px'>
                        <div align=center>
                            <svg id="barcode2"></svg>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td width=60% colspan=3 style='border:solid 1px;'>
                        <p style="font-size:14px">
                            供应商：{$back.vendor_name}</p>
                    </td>
                    <td width=40% colspan=4 style='border:solid 1px'>
                        <p style="font-size:14px"> 退回单号：{$back.back_code}</p>
                    </td>
                </tr>
                <tr>
                    <td colspan=3 style='max-width:450px;border:solid 1px'>
                        <p style="font-size:14px">收货单位：
                            {$back.address}
                            {if $back.contacts.length != ''}
                                <br>
                                联系方式：{$back.contacts}
                            {/if}
                        </p>
                    </td>
                    <td colspan=4 style='max-width:350px;border:solid 1px;line-height:22px'>
                        <p style="font-size:14px">
                            供应商代码：{$back.vendor_code}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style='max-width:35px;border:solid 1px;line-height:20px'>
                        <p style="font-size:14px">No.</p>
                    </td>
                    <td style='max-width:150px;border:solid 1px'>
                        <p style="font-size:14px">物料图号</p>
                    </td>
                    <td style='max-width:230px;border:solid 1px'>
                        <p style="font-size:14px">物料名称</p>
                    </td>
                    <td style='max-width:75px;border:solid 1px'>
                        <p style="font-size:14px">
                            退回数量
                        </p>
                    </td>
                    <td style='max-width:75px;border:solid 1px'>
                        <p style="font-size:14px">
                            实发数量
                        </p>
                    </td>
                    <td style='max-width:75px;border:solid 1px'>
                        <p style="font-size:14px">实收数量</p>
                    </td>
                    <td style='max-width:50px;border:solid 1px'>
                        <p style="font-size:14px">备注</p>
                    </td>
                </tr>
                {*{foreach $gds as $gd}*}
                {foreach from=$gds item=gd name=gdn}
                    <tr>
                        <td style='max-width:35px;border:solid 1px'>
                            <p style="font-size:14px">{$smarty.foreach.gdn.index + 1}
                            </p>
                        </td>
                        <td style='max-width:150px;border:solid 1px;word-wrap:break-word; word-break:normal;'>
                            <p style="font-size:14px;">{$gd.goods_ccode}</p>
                        </td>
                        <td style='max-width:230px;border:solid 1px'>
                            <p style="font-size:14px;word-break: break-all;">{$gd.goods_name}</p>
                        </td>
                        <td style='max-width:75px;border:solid 1px'>
                            <p style="font-size:14px">{$gd.needs}</p>
                        </td>
                        <td style='max-width:75px;border:solid 1px'>
                            <p style="font-size:14px">{$gd.needs}</p>
                        </td>
                        <td style='max-width:75px;border:solid 1px'>
                            <p style="font-size:14px"></p>
                        </td>
                        <td style='max-width:50px;border:solid 1px'>
                            <p style="font-size:14px">{$gd.remark}</p>
                        </td>
                    </tr>
                {/foreach}
            </table>
        {/if}

        <div align=left style="width:850px;padding-left:30px;padding-top: 30px">
            <div style="float:left;;width: 200px;font-size:16px;">
                送货员：
            </div>
            <div style="float:left;;width: 200px;font-size:16px;">
                送检员：
            </div>
            <div style="float:left;;width: 200px;font-size:16px;">
                检验员：
            </div>
            <div style="float:left;;width: 200px;font-size:16px;">
                收货员：
            </div>
        </div>
    </div>

    <div align=center style="width:850px">
        <div style="float:left;;width: 200px;font-size:12px;">
            第一页：保管员留存
        </div>
        <div style="float:left;;width: 200px;font-size:12px;">
            第二页：质检员留存
        </div>
        <div style="float:left;;width: 200px;font-size:12px;">
            第三页：采购员留存
        </div>
        <div style="float:left;;width: 200px;font-size:12px;">
            第四页：供应商留存
        </div>
    </div>

</div>

<script>
    var order_code = $('#order_code').val();
    if (order_code) {
        JsBarcode("#barcode", order_code, {
            height: 60,
            displayValue: true
        });
    }
    var back_code = $('#back_code').val();
    if (back_code) {
        JsBarcode("#barcode2", back_code, {
            height: 60,
            displayValue: true
        });
    }
    var order_date = $('#order_date').val().substr(0, 10);
    $('#order_date_p').html(order_date);
    var back_date = $('#back_date').val().substr(0, 10);
    $('#back_date_p').html(back_date);

</script>

</body>

</html>