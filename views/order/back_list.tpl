{include file='../__header_v2.tpl'}
{assign var="script_name" value="back_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="backListController" ng-app="ngApp">

    {include file='../order/modal_back_detail.html'}

    {literal}
        <div class="pheader clearfix">
            <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                             placeholder="输入单号按回车"/></div>
        </div>
        <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
            <thead>
            <tr>
                <th class="hidden">ID</th>
                <!--<th>客户</th>-->
                <th>退回类型</th>
                <th>供货商</th>
                <th>退回单号</th>
                <th>退回日期</th>
                <!--<th>道口</th>-->
                <th>状态</th>
                <th width="100px" class="text-center">操作</th>
            </tr>
            </thead>
            <tbody>
            <tr ng-repeat="back in backlist">
                <td class="hidden">{{back.id}}</td>
                <!--<td>{{order.customer_name}}</td>-->
                <td>{{back.back_typeX}}</td>
                <td>{{back.vendor_name}}</td>
                <td>{{back.back_code}}</td>
                <td>{{back.back_date}}</td>
                <!--<td>{{order.dock}}</td>-->
                <td>{{back.statusX}}</td>
                <td>
                    <a class="text-success" data-id="{{back.id}}" data-toggle="modal" data-target="#modal_back_detail"
                       href="#">查看</a>

                    <a ng-show="back.status == 'create'" class="text-danger" data-id="{{back.id}}"
                       ng-click="deleteBack($event)" href="#">删除</a>

                    <a ng-show="back.status == 'receive'" class="text-primary" data-id="{{back.id}}"
                       ng-click="confirmBack($event)" href="#">客服确认</a>

                    <a class="text-muted" href="?/Page/orderprint/backid={{back.id}}"
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
