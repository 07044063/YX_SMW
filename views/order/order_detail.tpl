{include file='../__header_v2.tpl'}
{assign var="script_name" value="order_detail_controller"}

<div class="pd15" ng-controller="orderDetailController" ng-app="ngApp">

    <input class="hidden" id="order_id" value="{$order_id}"/>

    {literal}
        <div class="panel panel-default">
            <table>
                <thead>
                <tr>
                    <th>类型：{{order.order_type}}</th>
                    <th>供货商：{{order.vendor_name}}</th>
                    <th>发货单号：{{order.order_code}}</th>
                    <th>流水号：{{order.order_serial_no}}</th>
                    <th>发货时间：{{order.order_date}}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>状态：{{order.statusX}}</th>
                    <th>收货方：{{order.address}}</th>
                    <th>采购订单：{{order.pur_no}}</th>
                    <th></th>
                    <th>联系方式：{{order.contacts}}</th>
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
                    <th>物料图号</th>
                    <th>物料名称</th>
                    <th>库区</th>
                    <th>条码编号</th>
                    <th>包装方式</th>
                    <th>包装数量</th>
                    <th>用倍量</th>
                    <th>需求数量</th>
                    <th>发货数量</th>
                    <th>备注</th>
                </tr>
                </thead>
                <tbody>
                <tr ng-repeat="goods in goodslist">
                    <td class="hidden"><span>{{goods.goods_id}}</span></td>
                    <td><span>{{goods.goods_ccode}}</span></td>
                    <td><span>{{goods.goods_name}}</span></td>
                    <td><span>{{goods.stock_name}}</span></td>
                    <td><span>{{goods.bar_code}}</span></td>
                    <td><span>{{goods.packing}}</span></td>
                    <td><span>{{goods.packing_volume}}</span></td>
                    <td><span>{{goods.using_count}}</span></td>
                    <td><span>{{goods.needs}}</span></td>
                    <td><span>{{goods.sends}}</span></td>
                    <td><span>{{goods.remark}}</span></td>
                </tr>
                </tbody>
            </table>
        </div>
    {/literal}

</div>

<script src="{$docroot}static/script/lib/select2/select2.full.min.js"></script>

<script type="text/javascript" src="{$docroot}static/script/order/{$script_name}.js?v={$cssversion}"></script>


{include file='../__footer_v2.tpl'}
