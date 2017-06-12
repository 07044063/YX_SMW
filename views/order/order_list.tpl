{include file='../__header_v2.tpl'}
{assign var="script_name" value="order_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="orderListController" ng-app="ngApp" uploader="uploader">

    {include file='../order/modal_order_status.html'}
    {include file='../order/modal_order_import.html'}

    {literal}
        <div class="pheader clearfix">
            <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                             placeholder="输入单号或流水号按回车"/></div>
            <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
                <a class="btn btn-success" href="#" data-toggle="modal" data-target="#modal_order_import">Excel导入</a>
                <a class="btn btn-success" href="#">添加</a>
                <!--<a class="btn btn-primary" ng-click="refresh($event)">刷新</a>-->
            </div>
        </div>
        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <th>客户</th>
                <th>收货单号</th>
                <th>流水号</th>
                <th>类型</th>
                <th>供货商</th>
                <th>送货时间</th>
                <th>道口</th>
                <th>状态</th>
                <th width="100px" class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="order in orderlist">
                <td class="hidden">{{order.id}}</td>
                <td>{{order.customer_name}}</td>
                <td>{{order.order_code}}</td>
                <td>{{order.order_serial_no}}</td>
                <td>{{order.order_type}}</td>
                <td>{{order.vendor_name}}</td>
                <td>{{order.order_date}}</td>
                <td>{{order.dock}}</td>
                <td>{{order.statusX}}</td>
                <td>
                    <a class="text-success" href="?/Page/orderdetail/id={{order.id}}">查看明细</a>
                    <a class="text-info" data-id="{{order.id}}" data-toggle="modal" data-target="#modal_order_status"
                       href="#">状态变更</a>
                </td>
            </tr>
            </tbody>
        </table>
    {/literal}
</div>

<script type="text/javascript" src="{$docroot}static/script/lib/angular-file-upload.js"></script>
<script type="text/javascript" src="{$docroot}static/script/order/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
