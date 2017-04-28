{include file='../__header_v2.tpl'}
{assign var="script_name" value="warehouse_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="warehouseListController" ng-app="ngApp">

    {include file='../mdata/modal_modify_warehouse.html'}

    {literal}

    <div class="pheader clearfix">
        <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                         placeholder="输入编号或名称按回车"/></div>
        <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#modal_modify_warehouse">添加</a>
            <!--<a class="btn btn-primary" ng-click="refresh($event)">刷新</a>-->
        </div>
    </div>

    <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
        <thead>
        <tr>
            <th class="hidden">ID</th>
            <th>代码</th>
            <th>名称</th>
            <th>地址</th>
            <th width="100px" class="text-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="warehouses in warehouselist">
            <td class="hidden">{{warehouses.id}}</td>
            <td>{{warehouses.warehouse_code}}</td>
            <td>{{warehouses.warehouse_name}}</td>
            <td>{{warehouses.warehouse_address}}</td>
            <td>
                <a class="text-success" data-toggle="modal" data-target="#modal_modify_warehouse" data-id="{{warehouses.id}}"
                   href="#">编辑</a>
                <a class="text-danger" data-id="{{warehouses.id}}" ng-click="deleteWarehouse($event)" href="#">删除</a>
            </td>
        </tr>
        </tbody>
    </table>
</div>

{/literal}

</div>

<script type="text/javascript" src="{$docroot}static/script/mdata/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
