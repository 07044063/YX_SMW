{include file='../__header_v2.tpl'}
{assign var="script_name" value="order_list_controller"}

<div class="pd15" ng-controller="orderListController" ng-app="ngApp">

    {include file='../order/modal_order_status.html'}

    {literal}
        <div class="pheader clearfix">
            <div class="row">
                <div class="search-w-box">
                    <input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                                 placeholder="输入单号或流水号按回车"/>
                </div>
                <div class="form-group col-xs-2">
                    <select class="form-control" id="order_status"
                            ng-model="order_status" ng-change="selectChange()"
                            ng-init="order_status='all'"
                            ng-options="x as y for (x,y) in order_status_list">
                    </select>
                </div>
                <div class="form-group col-xs-3">
                    <select class="form-control" id="order_address"

                            ng-model="order_address" ng-change="selectChange()"
                            ng-init="order_address=address_list[0]"
                            ng-options="value for value in address_list">
                    </select>
                </div>
                <div class="form-group col-xs-2">

                    <select class="form-control" id="order_type"

                            ng-model="order_type" ng-change="selectChange()"
                            ng-init="order_type=order_type_list[0]"
                            ng-options="value for value in order_type_list">
                    </select>
                </div>
            </div>
        </div>
        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <!--<th>客户</th>-->
                <th>类型</th>
                <th>供货商</th>
                <th>收货单号</th>
                <th>流水号</th>
                <th>需求时间</th>
                <!--<th>道口</th>-->
                <th>状态</th>
                <th width="100px" class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="order in orderlist">
                <td class="hidden">{{order.id}}</td>
                <!--<td>{{order.customer_name}}</td>-->
                <td>{{order.order_type}}</td>
                <td>{{order.vendor_name}}</td>
                <td>{{order.order_code}}</td>
                <td>{{order.order_serial_no}}</td>
                <td>{{order.order_date}}</td>
                <!--<td>{{order.dock}}</td>-->
                <td>{{order.statusX}}</td>
                <td>
                    <a class="text-success" href="?/Page/orderdetail/id={{order.id}}" target="_blank">查看</a>

                    <a ng-show="order.status == 'create'" class="text-warning"
                       href="?/Page/orderedit/id={{order.id}}" target="_blank">修改</a>

                    <a ng-show="order.status == 'create'" class="text-danger" data-id="{{order.id}}"
                       ng-click="deleteOrder($event)" href="#">删除</a>

                    <a class="text-info" data-id="{{order.id}}" data-toggle="modal" data-target="#modal_order_status"
                       href="#">状态</a>

                    <a ng-show="order.status == 'delivery'" class="text-primary"
                       href="?/Page/orderconfirm/id={{order.id}}" target="_blank">客服确认</a>

                    <a ng-show="order.order_type == '手工单'" class="text-muted" href="?/Page/orderprint/orderid={{order.id}}"
                       target="_blank">打印</a>
                </td>
            </tr>
            </tbody>
        </table>

    {/literal}
</div>

<script type="text/javascript" src="{$docroot}static/script/order/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
