<?php /** Created by conghu on 2017/5/8.**/
?>
{include file='../__header_v2.tpl'}
{assign var="script_name" value="stockloan_list_controller"}
<style type="text/css">
    td {
        padding: 0px 6px !important;
    }
</style>
<div class="pd15" ng-controller="stockLoanListController" ng-app="ngApp">

    {include file='../mdata/modal_modify_stockloan.html'}

    {literal}

    <div class="pheader clearfix">
        <div class="search-w-box"><input type="text" id="search_text" ng-model="search_text" class="searchbox"
                                         placeholder="输入厂商或仓库信息按回车"/></div>
        <div class="button-set" style="margin-top: 13px;margin-right: 13px;">
            <a class="btn btn-success" href="#" data-toggle="modal" data-target="#modal_modify_stockloan">添加</a>
            <!--<a class="btn btn-primary" ng-click="refresh($event)">刷新</a>-->
        </div>
    </div>

    <table class="table table-hover table-bordered" style="margin-bottom: 50px;">
        <thead>
        <tr>
            <th class="hidden">ID</th>
            <th>仓库代码</th>
            <th>仓库名称</th>
            <th>供货商代码</th>
            <th>供货商名称</th>
            <th>供货商简码</th>
            <th>租赁单价</th>
            <th>租赁面积</th>
            <th>其他描述</th>
            <th width="100px" class="text-center">操作</th>
        </tr>
        </thead>
        <tbody>
        <tr ng-repeat="rowStockLoan in stockloanlist">
            <td class="hidden">{{rowStockLoan.id}}</td>
            <td>{{rowStockLoan.stock_code}}</td>
            <td>{{rowStockLoan.stock_name}}</td>
            <td>{{rowStockLoan.vendor_code}}</td>
            <td>{{rowStockLoan.vendor_name}}</td>
            <td>{{rowStockLoan.vendor_shortname}}</td>
            <td>{{rowStockLoan.price}}</td>
            <td>{{rowStockLoan.area}}</td>
            <td>{{rowStockLoan.remark}}</td>
            <td>
                <a class="text-success" data-toggle="modal" data-target="#modal_modify_stockloan" data-id="{{rowStockLoan.id}}"
                   href="#">编辑</a>
                <a class="text-danger" data-id="{{rowStockLoan.id}}" ng-click="deleteStockloan($event)" href="#">删除</a>
            </td>
        </tr>
        </tbody>
    </table>

    {/literal}

</div>

<script type="text/javascript" src="{$docroot}static/script/mdata/{$script_name}.js"></script>

<div class="navbar-fixed-bottom bottombar">
    <div id="pager-bottom">
        <ul class="pagination-sm pagination"></ul>
    </div>
</div>

{include file='../__footer_v2.tpl'}
