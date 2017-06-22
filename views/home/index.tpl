{include file='../__header_v2.tpl'}
{assign var="script_name" value="home/index"}

<style type="text/css">
    .nav-tabs > li > a {
        border-radius: 0;
    }
</style>

<div class="pd15">

    <span class="hidden"><i id="order_delivery">{$datas.order_delivery}</i></span>
    <span class="hidden"><i id="order_send">{$datas.order_send}</i></span>
    <span class="hidden"><i id="order_check">{$datas.order_check}</i></span>
    <span class="hidden"><i id="order_receive">{$datas.order_receive}</i></span>
    <span class="hidden"><i id="order_create">{$datas.order_create}</i></span>

    <span class="hidden"><i id="catdata">{$catdata}</i></span>

    <div class="row">
        <div class="col-xs-6">
            <div class="panel panel-default">
                <div class="panel-heading">三日内发货单状况一览</div>
                <div id="order-percent2" style="height: 300px;"></div>
            </div>
        </div>
        <div class="col-xs-6">
            <div class="panel panel-default">
                <div class="panel-heading">发货供应商分布图</div>
                <div id="order-percent" style="height: 300px;"></div>
                {*<div class="fansStatChart" style="height: 300px;"></div>*}
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">数据统计</div>
        <table class="ovw-table">

            <tr>
                <td>
                    <span class="green">{$datas.today}<b>单</b></span>
                    <span>今日发货单</span>
                </td>
                <td>
                    <span class="green">{$datas.today_done}<b>单</b></span>
                    <span>已完成</span>
                </td>
                <td>
                    <span>{$datas.today_check}<b>单</b></span>
                    <span>已发货</span>
                </td>
                <td>
                    <span>{$datas.today_create}<b>单</b></span>
                    <span>未发货</span>
                </td>
            </tr>

        </table>
    </div>

</div>

<script type="text/javascript" src="{$docroot}static/script/lib/highcharts.js"></script>
<script type="text/javascript" src="{$docroot}static/script/{$script_name}.js"></script>

{include file='../__footer_v2.tpl'}
