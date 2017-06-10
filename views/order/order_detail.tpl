{include file='../__header_v2.tpl'}
{assign var="script_name" value="orderdetail_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="orderDetailController" ng-app="ngApp">

    <input class="hidden" id="order_id" value="{$order_id}"/>

    {literal}
        <div class="panel panel-default">
            <table>
                <thead>
                <tr>
                    <th>收货单信息</th>
                    <th>收货单号：{{order.order_code}}</th>
                    <th>流水号：{{order.order_serial_no}}</th>
                    <th></th>
                    <th></th>
                    <th>
                        <button type="button" class="btn btn-success pull-right" style="margin-right: 15px"
                                ng-click="goBack();">返回
                        </button>
                    </th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><span>客户：{{order.customer_name}}</span></td>
                    <td><span>供货商：{{order.vendor_name}}</span></td>
                    <td><span>类型：{{order.order_type}}</span></td>
                    <td><span>收货时间：{{order.order_date}}</span></td>
                    <td><span>道口：{{order.dock}}</span></td>
                    <td><span>状态：{{order.statusX}}</span></td>
                </tr>
                </tbody>
            </table>

        </div>
        <div class="panel panel-default">
            <div class="panel-heading">物料清单</div>
            <table>
                <thead>
                <tr>
                    <th class="hidden">GOODSID</th>
                    <th>供货商名称</th>
                    <th>物料图号</th>
                    <th>物料名称</th>
                    <th>库区</th>
                    <th>条码编号</th>
                    <th>包装方式</th>
                    <th>包装数量</th>
                    <th class="text-cente">需求数量</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="goods in goodslist">
                    <td class="hidden"><span>{{goods.goods_id}}</span></td>
                    <td><span>{{goods.vendor_name}}</span></td>
                    <td><span>{{goods.goods_ccode}}</span></td>
                    <td><span>{{goods.goods_name}}</span></td>
                    <td><span>{{goods.stock_name}}</span></td>
                    <td><span>{{goods.bar_code}}</span></td>
                    <td><span>{{goods.packing}}</span></td>
                    <td><span>{{goods.packing_volume}}</span></td>
                    <td><span>{{goods.needs}}</span></td>
                    <td><span>{{goods.remark}}</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    {/literal}

</div>

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>

<script type="text/javascript" src="{$docroot}static/script/order/{$script_name}.js"></script>


{include file='../__footer_v2.tpl'}
